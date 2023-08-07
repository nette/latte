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


class ListNode extends Node
{
	public function __construct(
		/** @var array<ListItemNode|null> */
		public array $items = [],
		public ?Position $position = null,
	) {
		$this->validate();
	}


	public function print(PrintContext $context): string
	{
		$this->validate();
		return '[' . $context->implode($this->items) . ']';
	}


	public function validate(): void
	{
		foreach ($this->items as $item) {
			if ($item !== null && !$item instanceof ListItemNode) {
				throw new \TypeError('Item must be null or ListItemNode, ' . get_debug_type($item) . ' given.');
			} elseif ($item?->value instanceof ExpressionNode && !$item->value->isWritable()) {
				throw new CompileException('Cannot write to the expression: ' . $item->value->print(new PrintContext), $item->value->position);
			}
		}
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->items as &$item) {
			if ($item) {
				yield $item;
			}
		}
	}
}
