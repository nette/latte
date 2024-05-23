<?php

declare(strict_types=1);

namespace Latte\Compiler;


/**
 * @implements \IteratorAggregate<Node>
 */
abstract class Node implements \IteratorAggregate
{
	public ?Position $position = null;


	abstract public function print(PrintContext $context): string;


	/** @return \Generator<self> */
	abstract public function &getIterator(): \Generator;
}
