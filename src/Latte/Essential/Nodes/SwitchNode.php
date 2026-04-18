<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {switch $expr} {case $val} ... {default} ... {/switch}
 * Uses strict comparison (===) without fallthrough.
 */
class SwitchNode extends StatementNode
{
	public ?ExpressionNode $expression;

	/** @var array<array{?ArrayNode, FragmentNode}> */
	public array $cases = [];


	/** @return \Generator<int, ?list<string>, array{FragmentNode, Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		if ($tag->isNAttribute()) {
			throw new CompileException('Attribute n:switch is not supported.', $tag->position);
		}

		$node = $tag->node = new static;
		$node->expression = $tag->parser->isEnd()
			? null
			: $tag->parser->parseExpression();

		[$content, $nextTag] = yield ['case', 'default'];
		foreach ($content->children as $child) {
			if (!$child instanceof TextNode || !$child->isWhitespace()) {
				throw new CompileException('No content is allowed between {switch} and {case}', $child->position);
			}
		}

		$default = 0;
		while (true) {
			if ($nextTag->name === 'case') {
				$nextTag->expectArguments();
				$case = $nextTag->parser->parseArguments();
				[$content, $nextTag] = yield ['case', 'default'];
				$node->cases[] = [$case, $content];

			} elseif ($nextTag->name === 'default') {
				if ($default++) {
					throw new CompileException('Tag {switch} may only contain one {default} clause.', $nextTag->position);
				}
				[$content, $nextTag] = yield ['case', 'default'];
				$node->cases[] = [null, $content];

			} else {
				return $node;
			}
		}
	}


	public function print(PrintContext $context): string
	{
		$res = $context->format(
			'$ʟ_switch = (%node) %line;',
			$this->expression,
			$this->position,
		);
		$first = true;
		$default = null;
		foreach ($this->cases as $i => [$case, $content]) {
			if (!$case) {
				$default = $content->print($context);
				continue;
			} elseif (!$first) {
				$res .= 'else';
			}

			$first = false;
			$single = count($case->items) === 1 && !$case->items[0]->unpack;
			$res .= $context->format(
				$single
					? 'if ($ʟ_switch === (%node)) %line { %node } '
					: 'if (in_array($ʟ_switch, %node, true)) %line { %node } ',
				$single ? $case->items[0]->value : $case,
				$this->tagRanges[$i + 1] ?? null,
				$content,
			);
		}

		if ($default) {
			$res .= $first ? $default : 'else { ' . $default . ' } ';
		}
		return $res;
	}


	public function &getIterator(): \Generator
	{
		if ($this->expression) {
			yield $this->expression;
		}
		foreach ($this->cases as [&$case, &$stmt]) {
			if ($case) {
				yield $case;
			}
			yield $stmt;
		}
	}
}
