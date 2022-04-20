<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {iterateWhile $cond}
 */
class IterateWhileNode extends StatementNode
{
	public ExpressionNode $condition;
	public AreaNode $content;
	public ?ExpressionNode $key;
	public ExpressionNode $value;
	public bool $postTest;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$foreach = $tag->closest(['foreach']);
		if (!$foreach) {
			throw new CompileException("Tag {{$tag->name}} must be inside {foreach} ... {/foreach}.", $tag->position);
		}

		$node = new static;
		$node->postTest = $tag->parser->isEnd();
		if (!$node->postTest) {
			$node->condition = $tag->parser->parseExpression();
		}

		[$node->key, $node->value] = $foreach->data->iterateWhile;
		[$node->content, $nextTag] = yield;
		if ($node->postTest) {
			$nextTag->expectArguments();
			$node->condition = $nextTag->parser->parseExpression();
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		$stmt = $context->format(
			<<<'XX'
				if (!$iterator->hasNext() || !(%raw)) {
					break;
				}
				$iterator->next();
				[%raw, %raw] = [$iterator->key(), $iterator->current()];
				XX,
			$this->condition,
			$this->key,
			$this->value,
		);

		return $context->format(
			<<<'XX'
				do %line {
					%raw
					%raw
				} while (true);

				XX,
			$this->position,
			...($this->postTest ? [$this->content, $stmt] : [$stmt, $this->content]),
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
		yield $this->content;
	}
}
