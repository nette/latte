<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php\{ArgumentNode, ArrayItemNode, ExpressionNode};
use Latte\Compiler\{Position, PrintContext};
use Latte\Helpers;


/**
 * Array literal.
 */
class ArrayNode extends ExpressionNode
{
	public function __construct(
		/** @var array<ArrayItemNode> */
		public array $items = [],
		public ?Position $position = null,
		public ?Position $end = null,
	) {
		(function (ArrayItemNode ...$args) {})(...$items);
	}


	/** @return ArgumentNode[] */
	public function toArguments(): array
	{
		return array_map(fn(ArrayItemNode $item) => $item->toArgument(), $this->items);
	}


	/**
	 * Checks that the items can be passed to parameters, i.e. no positional item follows a named one.
	 */
	public function validateArguments(): void
	{
		$named = false;
		foreach ($this->items as $item) {
			if ($item->key) {
				$named = true;
			} elseif ($named) {
				trigger_error("Positional argument after a named argument is deprecated ($item->position)", E_USER_DEPRECATED);
				return;
			}
		}
	}


	public function print(PrintContext $context): string
	{
		return '[' . $context->implode($this->items) . ']';
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->items as &$item) {
			yield $item;
		}
		Helpers::removeNulls($this->items);
	}
}
