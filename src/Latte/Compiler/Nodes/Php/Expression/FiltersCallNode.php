<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\FilterNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class FiltersCallNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $expr,
		/** @var FilterNode[] */
		public array $filters,
		public ?Position $position = null,
	) {
		(function (FilterNode ...$args) {})(...$filters);
	}


	public function print(PrintContext $context): string
	{
		$expr = $this->expr->print($context);
		foreach ($this->filters as $filter) {
			$expr = $filter->printSimple($context, $expr);
		}
		return $expr;
	}


	public function &getIterator(): \Generator
	{
		yield $this->expr;
		foreach ($this->filters as &$filter) {
			yield $filter;
		}
	}
}
