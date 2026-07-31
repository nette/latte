<?php declare(strict_types=1);

namespace Latte\Compiler;


/**
 * Base class for AST nodes representing parsed template structure.
 *
 * Each node must implement print() to generate PHP code and getIterator()
 * to yield child nodes for compiler passes.
 *
 * @implements \IteratorAggregate<Node>
 */
abstract class Node implements \IteratorAggregate
{
	public ?Position $position = null;
	public ?Position $end = null;


	abstract public function print(PrintContext $context): string;


	/** @return \Generator<self> */
	abstract public function &getIterator(): \Generator;


	/**
	 * Yields a child list by reference and prunes removed items. Iterate it, never return it.
	 * @template T of self
	 * @template TList of array<T>|null
	 * @param  TList  $list
	 * @param-out  ($list is null ? null : list<T>)  $list
	 * @param  ?\Closure(T ...): void  $type  re-checks item types after the walk
	 * @return \Generator<T>
	 */
	protected static function &walk(?array &$list, ?\Closure $type = null): \Generator
	{
		if ($list === null) {
			return;
		}

		try {
			foreach ($list as &$item) {
				yield $item;
			}
		} finally { // StopTraversal discards the generator before its end
			$list = array_values(array_filter($list, fn(?self $item) => $item !== null)); // the traverser removes an item by writing null
			$type?->__invoke(...$list);
		}
	}
}
