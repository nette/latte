<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ListNode;
use Latte\Compiler\PrintContext;


/**
 * Temporary container for partial parsing results (e.g., array destructuring).
 * @internal
 */
class TemporaryNode extends ExpressionNode
{
	public function __construct(
		public ?ListNode $value,
	) {
	}


	public function print(PrintContext $context): string
	{
		return '';
	}


	public function &getIterator(): \Generator
	{
		false && yield;
	}
}
