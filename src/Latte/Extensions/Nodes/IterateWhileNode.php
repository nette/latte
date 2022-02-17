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
 * {iterateWhile $cond}
 */
class IterateWhileNode extends StatementNode
{
	public ExpressionNode $condition;
	public Node $content;
	public string $args;
	public bool $postTest;


	/** @return \Generator<int, ?array, array{FragmentNode, ?TagInfo}, self> */
	public static function parse(TagInfo $tag): \Generator
	{
		$tag->validate(null);
		$foreach = $tag->closest(['foreach']);
		if (!$foreach) {
			throw new CompileException("Tag {{$tag->name}} must be inside {foreach} ... {/foreach}.");
		}

		$node = new self;
		$node->condition = $tag->tokenizer;
		$node->postTest = $tag->args === '';
		$node->args = preg_replace('#^.+\s+as\s+(?:(.+)=>)?(.+)$#i', '$1, $2', $foreach->data->iterateWhile);
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
		$stmt = <<<XX
			if (!\$iterator->hasNext() || !({$compiler->write('%args', $this->condition)})) {
				break;
			}
			\$iterator->next();
			[{$this->args}] = [\$iterator->key(), \$iterator->current()];
			XX;
		$content = $this->content->compile($compiler);

		return $compiler->write(
			<<<'XX'
				do %line {
					%raw
					%raw
				} while (true);

				XX,
			$this->line,
			...($this->postTest ? [$content, $stmt] : [$stmt, $content]),
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
		yield $this->content;
	}
}
