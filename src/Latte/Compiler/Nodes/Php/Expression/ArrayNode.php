<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ArrayNode extends ExpressionNode
{
	public function __construct(
		/** @var array<ArrayItemNode|null> */
		public array $items = [],
		public ?Position $position = null,
	) {
	}


	public static function fromArray(array $arr): static
	{
		$node = new static;
		$lastKey = -1;
		foreach ($arr as $key => $val) {
			if ($lastKey !== null && ++$lastKey === $key) {
				$node->items[] = new ArrayItemNode(self::fromValue($val));
			} else {
				$lastKey = null;
				$node->items[] = new ArrayItemNode(self::fromValue($val), self::fromValue($key));
			}
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return '[' . $context->implode($this->items) . ']';
	}


	public function printAsArguments(PrintContext $context): string
	{
		$args = [];
		foreach ($this->items as $item) {
			if ($item !== null) {
				$args[] = $item->printAsArgument($context);
			}
		}

		return implode(', ', $args);
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
