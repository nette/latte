<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


/**
 * Property access with nullsafe support ($obj->prop, $obj?->prop).
 */
class PropertyFetchNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $object,
		public IdentifierNode|ExpressionNode $name,
		public bool $nullsafe = false,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->dereferenceExpr($this->object)
			. ($this->nullsafe ? '?->' : '->')
			. $context->objectProperty($this->name);
	}


	public function &getIterator(): \Generator
	{
		yield $this->object;
		yield $this->name;
	}
}
