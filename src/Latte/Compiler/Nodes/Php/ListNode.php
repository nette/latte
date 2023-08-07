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


class ListNode extends Node
{
	public function __construct(
		/** @var array<ListItemNode|null> */
		public array $items = [],
		public ?Position $position = null,
	) {
		(function (?ListItemNode ...$args) {})(...$items);
	}


	public function print(PrintContext $context): string
	{
		return '[' . $context->implode($this->items) . ']';
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
