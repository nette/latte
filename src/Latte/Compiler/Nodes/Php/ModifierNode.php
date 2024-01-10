<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ModifierNode extends Node
{
	/** @var array<string, FilterNode> */
	public array $flags = [];
	public bool $check = false;


	public function __construct(
		/** @var FilterNode[] */
		public array $filters,
		public bool $escape = false,
		public ?Position $position = null,
	) {
		(function (FilterNode ...$args) {})(...$filters);
	}


	public function defineFlags(string ...$names): void
	{
		foreach ($this->filters as $i => $filter) {
			$name = $filter->name->name;
			if (in_array($name, $names, true)) {
				if ($filter->nullsafe || $filter->args) {
					throw new CompileException("Flag |$name cannot have arguments or nullsafe pipe.", $filter->position);
				}
				unset($this->filters[$i]);
				$this->flags[$name] = $filter;
			}
		}
		$this->escape = in_array('noescape', $names, true)
			? empty($this->flags['noescape'])
			: $this->escape;
	}


	public function hasFlag(string $name): bool
	{
		return isset($this->flags[$name]);
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
		$this->checkUnallowedFlags();
		$expr = FilterNode::printFilters($context, $this->filters, $expr);

		$escaper = $context->getEscaper();
		if ($this->check) {
			$expr = $escaper->check($expr);
		}

		$expr = $this->escape
			? $escaper->escape($expr)
			: $escaper->escapeMandatory($expr);

		return $expr;
	}


	public function printContentAware(PrintContext $context, string $expr): string
	{
		$this->checkUnallowedFlags();
		foreach ($this->filters as $filter) {
			$expr = $filter->printContentAware($context, $expr);
		}

		if ($this->escape) {
			$expr = 'LR\Filters::convertTo($ʟ_fi, '
				. var_export($context->getEscaper()->export(), true) . ', '
				. $expr
				. ')';
		}

		return $expr;
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->filters as &$filter) {
			yield $filter;
		}
		foreach ($this->flags as &$filter) {
			yield $filter;
		}
	}


	private function checkUnallowedFlags(): void
	{
		foreach ($this->filters as $filter) {
			$name = $filter->name->name;
			if ($this->escape && $name === 'noescape') { // back compatibility
				$this->defineFlags('noescape');
				$this->escape = false;
			} elseif ($name === 'noescape' || $name === 'nocheck' || $name === 'noCheck') {
				throw new CompileException("Filter |$name is not allowed here.", $filter->position);
			}
		}
	}
}
