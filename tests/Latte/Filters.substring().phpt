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
	private $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function __toString()
	{
		return (string)$this->name;
	}

	public function count()
	{
		return 0;
	}

	public function getIterator()
	{
		return new ArrayIterator([]);
	}
}

Assert::same('ě', Filters::substring(new CountableTraversableStringClass($s), 4, 1));