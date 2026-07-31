<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\{AreaNode, StatementNode};
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\{PrintContext, Tag};


/**
 * {for $i = 0; $i < 10; $i++} ... {/for}
 */
class ForNode extends StatementNode
{
	/** @var ExpressionNode[]  */
	public array $init = [];
	public ?ExpressionNode $condition;

	/** @var ExpressionNode[]  */
	public array $next = [];
	public AreaNode $content;


	/** @return \Generator<int, ?list<string>, array{AreaNode, ?Tag}, static> */
	public static function create(Tag $tag): \Generator
	{
		$tag->expectArguments();
		$stream = $tag->parser->stream;
		$node = $tag->node = new static;
		while (!$stream->is(';')) {
			$node->init[] = $tag->parser->parseExpression();
			$stream->tryConsume(',');
		}

		$stream->consume(';');
		$node->condition = $stream->is(';') ? null : $tag->parser->parseExpression();
		$stream->consume(';');
		while (!$tag->parser->isEnd()) {
			$node->next[] = $tag->parser->parseExpression();
			$stream->tryConsume(',');
		}

		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				for (%args; %node; %args) %line {
					%node
				}

				XX,
			$this->init,
			$this->condition,
			$this->next,
			$this->position,
			$this->content,
		);
	}


	public function &getIterator(): \Generator
	{
		foreach (self::walk($this->init, fn(ExpressionNode ...$init) => null) as &$item) {
			yield $item;
		}

		if ($this->condition) {
			yield $this->condition;
		}

		foreach (self::walk($this->next, fn(ExpressionNode ...$next) => null) as &$item) {
			yield $item;
		}

		yield $this->content;
	}
}
