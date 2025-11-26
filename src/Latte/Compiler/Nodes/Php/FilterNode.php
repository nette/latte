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
use Latte\Helpers;


class FilterNode extends Node
{
	public function __construct(
		public IdentifierNode $name,
		/** @var ArgumentNode[] */
		public array $args = [],
		public bool $nullsafe = false,
		public ?Position $position = null,
	) {
		if ($name->name === 'escape') {
			throw new CompileException("Filter 'escape' is not allowed.", $position);
		}
		(function (ArgumentNode ...$args) {})(...$args);
	}


	public function print(PrintContext $context): string
	{
		throw new \LogicException('Cannot directly print FilterNode');
	}


	/** @param  self[]  $filters */
	public static function printSimple(PrintContext $context, array $filters, string $expr): string
	{
		$nullsafe = false;
		$chain = $expr;
		$tmp = '$ʟ_tmp';
		foreach ($filters as $filter) {
			if ($filter->nullsafe) {
				$expr = $nullsafe ? "(($tmp = $expr) === null ? null : $chain)" : $chain;
				$chain = $tmp;
				$nullsafe = true;
			}

			$chain = '($this->filters->' . $context->objectProperty($filter->name) . ')('
				. $chain
				. ($filter->args ? ', ' . $context->implode($filter->args) : '')
				. ')';
		}

		return $nullsafe ? "(($tmp = $expr) === null ? null : $chain)" : $chain;
	}


	public function printContentAware(PrintContext $context, string $expr): string
	{
		if ($this->nullsafe) {
			throw new CompileException('Content-aware filter cannot be nullsafe.', $this->position);
		}
		return '$this->filters->filterContent('
			. $context->encodeString($this->name->name)
			. ', $ʟ_fi, '
			. $expr
			. ($this->args ? ', ' . $context->implode($this->args) : '')
			. ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
		Helpers::removeNulls($this->args);
	}
}
