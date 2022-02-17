<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\CompileException;
use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {first [$width]}
 * {last [$width]}
 * {sep [$width]}
 */
class FirstLastSepNode extends StatementNode
{
	public string $name;
	public ?ExpressionNode $width = null;
	public Node $then;
	public ?Node $else = null;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->validate(null);
		if (!($tag->closest(['foreach']))) {
			throw new CompileException("Tag {{$tag->name}} must be inside {foreach} ... {/foreach}.");
		}

		$node = new self;
		$node->name = $tag->name;
		$node->width = $tag->tokenizer;
		if ($tag->empty) {
			$node->then = new NopNode;
			return $node;
		}

		[$node->then, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$nextTag->validate(false);
			[$node->else] = yield;
		}

		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$cond = match ($this->name) {
			'first' => '$iterator->isFirst',
			'last' => '$iterator->isLast',
			'sep' => '!$iterator->isLast',
		};
		return $compiler->write(
			$this->else
				? "if ($cond(%args)) %line { %raw } else %line { %raw }\n"
				: "if ($cond(%args)) %line { %raw }\n",
			$this->width,
			$this->line,
			$this->then->compile($compiler),
			$this->else?->line,
			$this->else?->compile($compiler),
		);
	}


	public function &getIterator(): \Generator
	{
		if ($this->width) {
			yield $this->width;
		}
		yield $this->then;
		if ($this->else) {
			yield $this->else;
		}
	}
}
