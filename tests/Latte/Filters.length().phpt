<?php

/**
 * Test: Latte\Runtime\Filters::length()
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(0,  Filters::length(''));
Assert::same(20,  Filters::length("I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n")); // Iñtërnâtiônàlizætiøn
Assert::same(2,  Filters::length(['one', 'two']));


class CountableClass implements Countable
{
	public function count()
	{
		return 4;
	}
}

Assert::same(4,  Filters::length(new CountableClass()));


class TraversableClass implements IteratorAggregate
{
	public function getIterator()
	{
		return new ArrayIterator(['one', 'two', 'three']);
	}
}

Assert::same(3,  Filters::length(new TraversableClass()));
