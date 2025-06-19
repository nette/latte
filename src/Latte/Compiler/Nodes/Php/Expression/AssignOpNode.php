<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use function in_array;


class AssignOpNode extends ExpressionNode
{
	private const Ops = ['+', '-', '*', '/', '.', '%', '&', '|', '^', '<<', '>>', '**', '??'];


	public function __construct(
		public ExpressionNode $var,
		public /*readonly*/ string $operator,
		public ExpressionNode $expr,
		public ?Position $position = null,
	) {
		if (!in_array($this->operator, self::Ops, true)) {
			throw new \InvalidArgumentException("Unexpected operator '$this->operator'");
		}
		$this->validate();
	}


	public function print(PrintContext $context): string
	{
		$this->validate();
		return $context->infixOp($this, $this->var, ' ' . $this->operator . '= ', $this->expr);
	}


	public function validate(): void
	{
		if (!$this->var->isWritable()) {
			throw new CompileException('Cannot write to the expression: ' . $this->var->print(new PrintContext), $this->var->position);
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
		yield $this->expr;
	}
}
