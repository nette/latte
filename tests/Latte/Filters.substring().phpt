<?php

/**
 * Test: Latte\Runtime\Filters::substring()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$s = "\u{158}ekn\u{11B}te, jak se (dnes) m\u{E1}te?"; // Řekněte, jak se (dnes) máte?


Assert::same('', Filters::substring('', 0));
Assert::same('', Filters::substring('', -1));
Assert::same('', Filters::substring('', 1));
Assert::same('?', Filters::substring($s, -1));
Assert::same('Řekněte, jak se (dnes) máte?', Filters::substring($s, 0));
Assert::same('Řekněte, jak se (dnes) máte?', Filters::substring($s, 0, 99));
Assert::same('ekněte, jak se (dnes) máte?', Filters::substring($s, 1));
Assert::same('ě', Filters::substring($s, 4, 1));
Assert::same('ěte, jak se (d', Filters::substring($s, 4, -10));


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
