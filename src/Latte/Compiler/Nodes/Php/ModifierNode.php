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
	public function __construct(
		/** @var FilterNode[] */
		public array $filters,
		public bool $escape = false,
		public bool $check = true,
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


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print ModifierNode');
	}


	public function printSimple(PrintContext $context, string $expr): string
	{
		$escape = $this->escape;
		$check = $this->check;
		foreach ($this->filters as $filter) {
			$name = $filter->name->name;
			if ($name === 'nocheck' || $name === 'noCheck') {
				$check = false;
			} elseif ($name === 'noescape') {
				$escape = false;
			} else {
				if ($name === 'datastream' || $name === 'dataStream') {
					$check = false;
				}
				$expr = $filter->printSimple($context, $expr);
			}
		}

		$escaper = $context->getEscaper();
		if ($check) {
			$expr = $escaper->check($expr);
		}

		$expr = $escape
			? $escaper->escape($expr)
			: $escaper->escapeMandatory($expr);

		return $expr;
	}


	public function printContentAware(PrintContext $context, string $expr): string
	{
		$escape = $this->escape;
		foreach ($this->filters as $filter) {
			$name = $filter->name->name;
			if ($name === 'noescape') {
				$escape = false;
			} else {
				$expr = $filter->printContentAware($context, $expr);
			}
		}

		return $escape
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
