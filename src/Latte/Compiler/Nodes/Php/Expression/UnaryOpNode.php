<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\OperatorNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class UnaryOpNode extends ExpressionNode implements OperatorNode
{
	private const Ops = ['+' => 1, '-' => 1, '~' => 1, '@' => 1, '!' => 1];


	public function __construct(
		public ExpressionNode $expr,
		public string $operator,
		public ?Position $position = null,
	) {
		if (!isset(self::Ops[$this->operator])) {
			throw new \InvalidArgumentException("Unexpected operator '$this->operator'");
		}
	}


	public function print(PrintContext $context): string
	{
		$pos = $this->expr instanceof self || $this->expr instanceof PreOpNode ? self::AssocLeft : self::AssocRight; // Enforce -(-$expr) instead of --$expr
		return $this->operator . $context->parenthesize($this, $this->expr, $pos);
	}


	public function getOperatorPrecedence(): array
	{
		return match ($this->operator) {
			'+', '-', '~', '@' => [240, self::AssocRight],
			'!' => [220, self::AssocRight],
		};
	}


	public function &getIterator(): \Generator
	{
		yield $this->expr;
	}
}
