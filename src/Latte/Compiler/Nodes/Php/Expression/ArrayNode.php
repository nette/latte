<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\ArgumentNode;
use Latte\Compiler\Nodes\Php\ArrayItemNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class ArrayNode extends ExpressionNode
{
	public function __construct(
		/** @var array<ArrayItemNode> */
		public array $items = [],
		public ?Position $position = null,
	) {
		(function (ArrayItemNode ...$args) {})(...$items);
	}


	/** @param  ArgumentNode[]  $args */
	public static function fromArguments(array $args): self
	{
		return new self(array_map(fn(ArgumentNode $arg) => $arg->toArrayItem(), $args));
	}


	/** @return ArgumentNode[] */
	public function toArguments(): array
	{
		return array_map(fn(ArrayItemNode $item) => $item->toArgument(), $this->items);
	}


	public function print(PrintContext $context): string
	{
		// Converts [...$var] -> $var, because PHP 8.0 doesn't support unpacking with string keys
		if (PHP_VERSION_ID < 80100) {
			$res = '[';
			$merge = false;
			foreach ($this->items as $item) {
				if ($item === null) {
					$res .= ', ';
				} elseif ($item->unpack) {
					$res .= '], ' . $item->value->print($context) . ', [';
					$merge = true;
				} else {
					$res .= $item->print($context) . ', ';
				}
			}

			$res = str_ends_with($res, ', ') ? substr($res, 0, -2) : $res;
			return $merge ? "array_merge($res])" : $res . ']';
		}

		return '[' . $context->implode($this->items) . ']';
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->items as &$item) {
			yield $item;
		}
	}
}
