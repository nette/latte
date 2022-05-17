<?php

/**
 * Test: Latte\Essential\Filters::length()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same(0, Filters::length(''));
Assert::same(20, Filters::length("I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n")); // Iñtërnâtiônàlizætiøn
Assert::same(2, Filters::length(['one', 'two']));


class CountableClass implements Countable
{
	public function count(): int
	{
		return 4;
	}
}

Assert::same(4, Filters::length(new CountableClass));


class TraversableClass implements IteratorAggregate
{
	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator(['one', 'two', 'three']);
	}
}

Assert::same(3, Filters::length(new TraversableClass));
