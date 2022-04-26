<?php

/**
 * Test: Latte\Runtime\CachingIterator basic usage.
 */

declare(strict_types=1);

use Latte\Runtime\CachingIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Two items in array', function () {
	$arr = ['Nette', 'Framework'];

	$iterator = new CachingIterator($arr);
	$iterator->rewind();
	Assert::true($iterator->valid());
	Assert::true($iterator->first);
	Assert::false($iterator->last);
	Assert::false($iterator->empty);
	Assert::false($iterator->even);
	Assert::true($iterator->odd);
	Assert::same(1, $iterator->counter);
});

$iterator = new CachingIterator([]);
Assert::exception(
	fn() => $iterator->undeclared(),
	LogicException::class,
	'Call to undefined method Latte\Runtime\CachingIterator::undeclared().',
);

Assert::exception(
	fn() => $iterator->rewnd(),
	LogicException::class,
	'Call to undefined method Latte\Runtime\CachingIterator::rewnd(), did you mean rewind()?',
);

Assert::exception(
	fn() => $iterator->undeclared = 'value',
	LogicException::class,
	'Attempt to write to undeclared property Latte\Runtime\CachingIterator::$undeclared.',
);

Assert::exception(
	fn() => $iterator->undeclared,
	LogicException::class,
	'Attempt to read undeclared property Latte\Runtime\CachingIterator::$undeclared.',
);
