<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {for $init; $cond; $next}
 */
class ForNode extends StatementNode
{
	public ExpressionNode $args;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$tag->expectArguments();
		$node = new static;
		$node->args = $tag->parser->parseExpression();
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				for (%node) %line {
					%node
				}

				XX,
			$this->args,
			$this->position,
			$this->content,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
		yield $this->content;
	}
}
