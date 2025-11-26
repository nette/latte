<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Helpers;


class ModifierNode extends Node
{
	/** @deprecated */
	public bool $check = true;


	public function __construct(
		/** @var FilterNode[] */
		public array $filters,
		public bool $escape = false,
		public ?Position $position = null,
	) {
		(function (FilterNode ...$args) {})(...$filters);
	}


	public function hasFilter(string $name): bool
	{
		foreach ($this->filters as $filter) {
			if ($filter->name->name === $name) {
				return true;
			}
		}

		return false;
	}


	public function removeFilter(string $name): ?FilterNode
	{
		foreach ($this->filters as $i => $filter) {
			if ($filter->name->name === $name) {
				return array_splice($this->filters, $i, 1)[0];
			}
		}

		return null;
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print ModifierNode');
	}


	public function printSimple(PrintContext $context, string $expr): string
	{
		$expr = FilterNode::printSimple($context, $this->filters, $expr);

		$escaper = $context->getEscaper();
		return $this->escape
			? $escaper->escape($expr)
			: $escaper->escapeMandatory($expr, $this->position);
	}


	public function printContentAware(PrintContext $context, string $expr): string
	{
		foreach ($this->filters as $filter) {
			$expr = $filter->printContentAware($context, $expr);
		}

		return $this->escape
			? $context->getEscaper()->escapeContent($expr)
			: $expr;
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->filters as &$filter) {
			yield $filter;
		}
		Helpers::removeNulls($this->filters);
	}
}
