<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class FilterCallNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $expr,
		public Php\FilterNode $filter,
		public ?Position $position = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$filters = [];
		for ($node = $this; $node instanceof self; $node = $node->expr) {
			array_unshift($filters, $node->filter);
		}

		return Php\FilterNode::printSimple($context, $filters, $node->print($context));
	}


	public function &getIterator(): \Generator
	{
		yield $this->expr;
		yield $this->filter;
	}
}
