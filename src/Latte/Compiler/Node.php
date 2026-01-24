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


	abstract public function print(PrintContext $context): string;


	/** @return \Generator<self> */
	abstract public function &getIterator(): \Generator;
}
