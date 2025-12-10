<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\OperatorNode;
use Latte\Compiler\Nodes\Php\Scalar\NullNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class TernaryNode extends ExpressionNode implements OperatorNode
{
	public function __construct(
		public ExpressionNode $cond,
		public ?ExpressionNode $if,
		public ?ExpressionNode $else,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->parenthesize($this, $this->cond, self::AssocLeft)
			. ($this->if ? ' ? ' . $this->if->print($context) . ' : ' : ' ?: ')
			. $context->parenthesize($this, $this->else ?? new NullNode, self::AssocRight);
	}


	public function getOperatorPrecedence(): array
	{
		return [100, self::AssocNone];
	}


	public function &getIterator(): \Generator
	{
		yield $this->cond;
		if ($this->if) {
			yield $this->if;
		}
		if ($this->else) {
			yield $this->else;
		}
	}
}
