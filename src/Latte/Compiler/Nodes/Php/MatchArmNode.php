<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\{Node, Position, PrintContext};


/**
 * Match expression arm with conditions and body.
 */
class MatchArmNode extends Node
{
	public function __construct(
		/** @var ExpressionNode[]|null */
		public ?array $conds,
		public ExpressionNode $body,
		public ?Position $position = null,
		public ?Position $end = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return ($this->conds ? $context->implode($this->conds) : 'default')
			. ' => '
			. $this->body->print($context);
	}


	public function &getIterator(): \Generator
	{
		foreach (self::walk($this->conds, fn(ExpressionNode ...$conds) => null) as &$item) {
			yield $item;
		}
		yield $this->body;
	}
}
