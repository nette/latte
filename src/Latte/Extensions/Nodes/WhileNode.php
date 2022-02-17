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
 * {while $cond}
 */
class WhileNode extends StatementNode
{
	public ExpressionNode $condition;
	public Node $content;
	public bool $postTest;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->validate(null);
		$node = new self;
		$node->condition = $tag->tokenizer;
		$node->postTest = $tag->args === '';
		if ($tag->empty) {
			$node->content = new NopNode;
		} else {
			[$node->content, $nextTag] = yield;
			if ($node->postTest) {
				$nextTag->validate(true);
				$node->condition = $nextTag->tokenizer;
			}
		}
		return $node;
	}


	public function compile(Compiler $compiler): string
	{
		return $this->postTest
			? $compiler->write(
				<<<'XX'
					do %line {
						%raw
					} while (%args);

					XX,
				$this->line,
				$this->content->compile($compiler),
				$this->condition,
			)
			: $compiler->write(
				<<<'XX'
					while (%args) %line {
						%raw
					}

					XX,
				$this->condition,
				$this->line,
				$this->content->compile($compiler),
			);
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
		yield $this->content;
	}
}
