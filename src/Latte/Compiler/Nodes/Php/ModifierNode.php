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
use Latte\Context;


class ModifierNode extends Node
{
	public function __construct(
		/** @var FilterNode[] */
		public array $filters,
		public ?Position $position = null,
	) {
	}


	public function addEscape(): static
	{
		if ($this->filters && end($this->filters)->name->name === FilterNode::NoEscape) {
			array_pop($this->filters);
		} else {
			$this->filters[] = new FilterNode(new IdentifierNode(FilterNode::Escape));
		}

		return $this;
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print ModifierNode');
	}


	public function printSimple(PrintContext $context, string $expr): string
	{
		$filters = $this->filters;
		if ($context->getEscapingContext()[1] === Context::HtmlAttributeUrl) {
			$expr = $this->checkUrl($filters, $expr);
		}

		foreach ($filters as $filter) {
			$expr = $filter->printSimple($context, $expr);
		}

		if ($context->getEscapingContext()[2] === Context::HtmlAttributeUnquoted) {
			$expr = "'\"' . $expr . '\"'";
		}
		return $expr;
	}


	public function printContentAware(PrintContext $context, string $expr): string
	{
		foreach ($this->filters as $filter) {
			$expr = $filter->printContentAware($context, $expr);
		}

		return $expr;
	}


	private function checkUrl(array &$filters, string $expr): string
	{
		$check = true;
		foreach ($filters as $i => $filter) {
			if (['nocheck' => 1, 'noCheck' => 1][$filter->name->name] ?? null) {
				unset($filters[$i]);
				$check = false;
			} elseif (['datastream' => 1, 'dataStream' => 1][$filter->name->name] ?? null) {
				$check = false;
			}
		}

		return $check ? 'LR\Filters::safeUrl(' . $expr . ')' : $expr;
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->filters as &$filter) {
			yield $filter;
		}
	}
}
