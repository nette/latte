<?php

/**
 * Test: Latte\Essential\Filters::truncate()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$s = "\u{158}ekn\u{11B}te, jak se (dnes) m\u{E1}te?"; // Řekněte, jak se (dnes) máte?

Assert::same('…', Filters::truncate($s, -1)); // length=-1
Assert::same('…', Filters::truncate($s, 0)); // length=0
Assert::same('…', Filters::truncate($s, 1)); // length=1
Assert::same('Ř…', Filters::truncate($s, 2)); // length=2
Assert::same('Ře…', Filters::truncate($s, 3)); // length=3
Assert::same('Řek…', Filters::truncate($s, 4)); // length=4
Assert::same('Řekn…', Filters::truncate($s, 5)); // length=5
Assert::same('Řekně…', Filters::truncate($s, 6)); // length=6
Assert::same('Řeknět…', Filters::truncate($s, 7)); // length=7
Assert::same('Řekněte…', Filters::truncate($s, 8)); // length=8
Assert::same('Řekněte,…', Filters::truncate($s, 9)); // length=9
Assert::same('Řekněte,…', Filters::truncate($s, 10)); // length=10
Assert::same('Řekněte,…', Filters::truncate($s, 11)); // length=11
Assert::same('Řekněte,…', Filters::truncate($s, 12)); // length=12
Assert::same('Řekněte, jak…', Filters::truncate($s, 13)); // length=13
Assert::same('Řekněte, jak…', Filters::truncate($s, 14)); // length=14
Assert::same('Řekněte, jak…', Filters::truncate($s, 15)); // length=15
Assert::same('Řekněte, jak se…', Filters::truncate($s, 16)); // length=16
Assert::same('Řekněte, jak se …', Filters::truncate($s, 17)); // length=17
Assert::same('Řekněte, jak se …', Filters::truncate($s, 18)); // length=18
Assert::same('Řekněte, jak se …', Filters::truncate($s, 19)); // length=19
Assert::same('Řekněte, jak se …', Filters::truncate($s, 20)); // length=20
Assert::same('Řekněte, jak se …', Filters::truncate($s, 21)); // length=21
Assert::same('Řekněte, jak se (dnes…', Filters::truncate($s, 22)); // length=22
Assert::same('Řekněte, jak se (dnes)…', Filters::truncate($s, 23)); // length=23
Assert::same('Řekněte, jak se (dnes)…', Filters::truncate($s, 24)); // length=24
Assert::same('Řekněte, jak se (dnes)…', Filters::truncate($s, 25)); // length=25
Assert::same('Řekněte, jak se (dnes)…', Filters::truncate($s, 26)); // length=26
Assert::same('Řekněte, jak se (dnes)…', Filters::truncate($s, 27)); // length=27
Assert::same('Řekněte, jak se (dnes) máte?', Filters::truncate($s, 28)); // length=28
Assert::same('Řekněte, jak se (dnes) máte?', Filters::truncate($s, 29)); // length=29
Assert::same('Řekněte, jak se (dnes) máte?', Filters::truncate($s, 30)); // length=30
Assert::same('Řekněte, jak se (dnes) máte?', Filters::truncate($s, 31)); // length=31
Assert::same('Řekněte, jak se (dnes) máte?', Filters::truncate($s, 32)); // length=32


class CountableTraversableStringClass implements Countable, IteratorAggregate
{
	public function __toString()
	{
		return 'Hello';
	}


	public function count(): int
	{
	}


	public function getIterator(): Iterator
	{
	}
}

// Filters::length() is not used
Assert::same('He…', Filters::truncate(new CountableTraversableStringClass($s), 3)); // length=13
