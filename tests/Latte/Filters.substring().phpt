<?php

/**
 * Test: Latte\Runtime\Filters::substring()
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$s = "\xc5\x98ekn\xc4\x9bte, jak se (dnes) m\xc3\xa1te?"; // Řekněte, jak se (dnes) máte?


Assert::same('?', Filters::substring($s, -1));
Assert::same('Řekněte, jak se (dnes) máte?', Filters::substring($s, 0));
Assert::same('Řekněte, jak se (dnes) máte?', Filters::substring($s, 0, 99));
Assert::same('ekněte, jak se (dnes) máte?', Filters::substring($s, 1));
Assert::same('ě', Filters::substring($s, 4, 1));


class CountableTraversableStringClass implements Countable, IteratorAggregate
{
	public function __toString()
	{
		return 'Hello';
	}


	public function count()
	{
	}


	public function getIterator()
	{
	}
}

// Filters::length() is not used
Assert::same('o', Filters::substring(new CountableTraversableStringClass($s), 4, 1));
