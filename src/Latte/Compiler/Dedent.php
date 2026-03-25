<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\FragmentNode;
use function count, explode, implode, preg_match, str_ends_with, str_starts_with, strlen, substr, trim;


/**
 * Removes one level of indentation introduced by paired Latte tags.
 *
 * Each paired tag ({if}, {foreach}, …) strips exactly one indent level
 * from its content. The indent unit is auto-detected from the first
 * content line; the tag's source column determines how much structural
 * indent to preserve.
 */
final class Dedent
{
	public static function apply(FragmentNode $fragment, Tag $startTag): void
	{
		$tagIndentLen = $startTag->position->column - 1;

		$textNodes = [];
		self::collectTextNodes($fragment, $textNodes);

		$baseIndent = self::detectIndent($textNodes, $startTag);
		if ($baseIndent === null || strlen($baseIndent) <= $tagIndentLen) {
			return;
		}

		self::stripIndent($textNodes, $baseIndent, $tagIndentLen);
	}


	/** @param list<Nodes\TextNode> $out */
	private static function collectTextNodes(Node $node, array &$out): void
	{
		if ($node instanceof Nodes\TextNode) {
			$out[] = $node;
		} elseif ($node instanceof Nodes\Html\ElementNode) {
			if ($node->content) {
				self::collectTextNodes($node->content, $out);
			}
		} else {
			foreach ($node as $child) {
				if ($child instanceof AreaNode) {
					self::collectTextNodes($child, $out);
				}
			}
		}
	}


	/** @param list<Nodes\TextNode> $textNodes */
	private static function detectIndent(array $textNodes, Tag $startTag): ?string
	{
		$inlineChecked = false;
		$lastNonEmpty = -1;
		for ($k = count($textNodes) - 1; $k >= 0; $k--) {
			if ($textNodes[$k]->content !== '') {
				$lastNonEmpty = $k;
				break;
			}
		}

		foreach ($textNodes as $idx => $node) {
			if ($node->content === '') {
				continue;
			}

			if (!$inlineChecked) {
				$inlineChecked = true;
				if ($node->position?->line === $startTag->position->line) {
					return null;
				}
			}

			$firstLineAtStart = $node->position?->column === 1;
			$lines = explode("\n", $node->content);
			$lineCount = count($lines);
			$lastContinues = !str_ends_with($node->content, "\n") && $idx < $lastNonEmpty;

			foreach ($lines as $j => $line) {
				$isLineStart = $j === 0 ? $firstLineAtStart : true;
				if (!$isLineStart || $line === '') {
					continue;
				}

				$hasContent = trim($line) !== '';
				$continuesWithExpr = !$hasContent && $j === $lineCount - 1 && $lastContinues;

				if ($hasContent) {
					preg_match('/^(\t+| +)/', $line, $m);
					return $m[1] ?? null;
				} elseif ($continuesWithExpr) {
					return $line;
				}
			}
		}

		return null;
	}


	/** @param list<Nodes\TextNode> $textNodes */
	private static function stripIndent(array $textNodes, string $baseIndent, int $tagIndentLen): void
	{
		foreach ($textNodes as $node) {
			if ($node->content === '') {
				continue;
			}

			$firstLineAtStart = $node->position?->column === 1;
			$lines = explode("\n", $node->content);
			$modified = false;

			foreach ($lines as $j => &$line) {
				$isLineStart = $j === 0 ? $firstLineAtStart : true;
				if (!$isLineStart || $line === '') {
					continue;
				}

				if (str_starts_with($line, $baseIndent)) {
					$line = substr($line, 0, $tagIndentLen) . substr($line, strlen($baseIndent));
					$modified = true;
				} elseif (trim($line) !== '') {
					throw new CompileException('Inconsistent indentation.', $node->position ? new Position($node->position->line + $j, 1) : null);
				}
			}

			unset($line);
			if ($modified) {
				$node->content = implode("\n", $lines);
			}
		}
	}
}
