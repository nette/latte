<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ListNode;
use Latte\Compiler\Nodes\Php\OperatorNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


/**
 * Assignment ($var = expr or $var = &$ref).
 */
class AssignNode extends ExpressionNode implements OperatorNode
{
	public const Precedence = [90, self::AssocRight];


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
		return $context->parenthesize($this, $this->var, self::AssocLeft)
			. ($this->byRef ? ' = &' : ' = ')
			. $context->parenthesize($this, $this->expr, self::AssocRight);
	}


	public function getOperatorPrecedence(): array
	{
		return self::Precedence;
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
