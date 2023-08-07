<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ListNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class AssignNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode|ListNode $var,
		public ExpressionNode $expr,
		public bool $byRef = false,
		public ?Position $position = null,
	) {
		$this->validate();
	}


	public function print(PrintContext $context): string
	{
		$this->validate();
		return $context->infixOp($this, $this->var, $this->byRef ? ' = &' : ' = ', $this->expr);
	}


	public function validate(): void
	{
		if ($this->var instanceof ExpressionNode && !$this->var->isWritable()) {
			throw new CompileException('Cannot write to the expression: ' . $this->var->print(new PrintContext), $this->var->position);
		} elseif ($this->byRef && !$this->expr->isWritable()) {
			throw new CompileException('Cannot take reference to the expression: ' . $this->expr->print(new PrintContext), $this->expr->position);
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
		yield $this->expr;
	}
}
