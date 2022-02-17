<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Extensions\Nodes;

use Latte\Compiler\Compiler;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\TagInfo;


/**
 * {for $init; $cond; $next}
 */
class ForNode extends StatementNode
{
	public ExpressionNode $args;
	public Node $content;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->validate(true);
		$node = new self;
		$node->args = $tag->tokenizer;
		[$node->content] = $tag->empty
			? [new NopNode]
			: yield;
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		$args = $this->args->compile($compiler);
		$content = $this->content->compile($compiler);
		return $compiler->write(
			<<<'XX'
				for (%raw) %line {
					%raw
				}

				XX,
			$args,
			$this->line,
			$content,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
		yield $this->content;
	}
}
