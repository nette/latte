<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Helpers;


/**
 * Chain of filters with auto-escape flag.
 */
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


	/**
	 * Removes and returns a matching filter, or null if no matching filter is found at the requested position.
	 * Position: '' (default) = anywhere, 'first' = only at index 0, 'last' = only at the last index.
	 */
	public function removeFilter(string $name, string $position = ''): ?FilterNode
	{
		$indexes = match ($position) {
			'' => array_keys($this->filters),
			'first' => $this->filters ? [array_key_first($this->filters)] : [],
			'last' => $this->filters ? [array_key_last($this->filters)] : [],
			default => throw new \InvalidArgumentException("Invalid position '$position', expected '', 'first' or 'last'."),
		};
		foreach ($indexes as $i) {
			if ($this->filters[$i]->name->name === $name) {
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
