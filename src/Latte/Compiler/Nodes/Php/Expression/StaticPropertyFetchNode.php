<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\{ExpressionNode, NameNode, VarLikeIdentifierNode};
use Latte\Compiler\{Position, PrintContext};


/**
 * Static property access (Foo::$prop).
 */
class StaticPropertyFetchNode extends ExpressionNode
{
	public function __construct(
		public NameNode|ExpressionNode $class,
		public VarLikeIdentifierNode|ExpressionNode $name,
		public ?Position $position = null,
		public ?Position $end = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->dereferenceExpr($this->class)
			. '::$'
			. $context->objectProperty($this->name);
	}


	public function &getIterator(): \Generator
	{
		yield $this->class;
		yield $this->name;
	}
}
