<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use function count;


/**
 * Iterates over key-value pairs.
 * @internal
 * @template K
 * @template V
 * @implements \IteratorAggregate<K, V>
 */
class AuxiliaryIterator implements \IteratorAggregate, \Countable
{
	/**
	 * @param  array<array{K, V}>  $pairs
	 */
	public function __construct(
		private array $pairs,
	) {
	}


	/**
	 * @return \Generator<K, V>
	 */
	public function getIterator(): \Generator
	{
		foreach ($this->pairs as [$key, $value]) {
			yield $key => $value;
		}
	}


	public function count(): int
	{
		return count($this->pairs);
	}
}
