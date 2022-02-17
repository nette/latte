<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\TagInfo;


/**
 * {switch} ... {case} ... {default}
 */
class SwitchNode extends StatementNode
{
	public ExpressionNode $arg;
	public array $cases = [];


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->validate(null);

		$node = new self;
		$node->arg = $tag->tokenizer;
		if ($tag->empty) {
			$node->content = new NopNode;
			return $node;
		}

		[$content, $nextTag] = yield ['case', 'default'];
		foreach ($content->children as $child) {
			if (!$child instanceof TextNode || trim($child->content) !== '') {
				throw new CompileException('No content is allowed between {switch} and {case}');
			}
		}

		$default = 0;
		while (true) {
			if ($nextTag?->name === 'case') {
				$nextTag->validate(true);
				$tmp = $nextTag->tokenizer;
				[$content, $nextTag] = yield ['case', 'default'];
				$node->cases[] = [$tmp, $content];

			} elseif ($nextTag?->name === 'default') {
				if ($default++) {
					throw new CompileException('Tag {switch} may only contain one {default} clause.');
				}
				$nextTag->validate(false);
				[$content, $nextTag] = yield ['case', 'default'];
				$node->cases[] = [null, $content];

			} else {
				return $node;
			}
		}
	}


	public function compile(Compiler $compiler): string
	{
		$res = $compiler->write(
			'$ʟ_switch = (%raw) %line;',
			$this->arg->compile($compiler),
			$this->line,
		);
		$first = true;
		$default = null;
		foreach ($this->cases as [$condition, $stmt]) {
			if (!$condition) {
				$default = $stmt->compile($compiler);
				continue;
			} elseif (!$first) {
				$res .= 'else';
			}

			$first = false;
			$res .= $compiler->write(
				'if (in_array($ʟ_switch, %array, true)) %line { %raw } ',
				$condition,
				$condition->line,
				$stmt->compile($compiler),
			);
		}

		if ($default) {
			$res .= $first ? $default : 'else { ' . $default . ' } ';
		}
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->arg;
		foreach ($this->cases as [&$case, &$stmt]) {
			if ($case) {
				yield $case;
			}
			yield $stmt;
		}
	}
}
