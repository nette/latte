<?php

/**
 * Test: CachingIterator basic usage.
 */

declare(strict_types=1);

use Latte\Extensions\CachingIterator;
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

Assert::exception(function () {
	$iterator = new CachingIterator([]);
	$iterator->undeclared();
}, LogicException::class, 'Call to undefined method Latte\Extensions\CachingIterator::undeclared().');

Assert::exception(function () {
	$iterator = new CachingIterator([]);
	$iterator->rewnd();
}, LogicException::class, 'Call to undefined method Latte\Extensions\CachingIterator::rewnd(), did you mean rewind()?');

Assert::exception(function () {
	$iterator = new CachingIterator([]);
	$iterator->undeclared = 'value';
}, LogicException::class, 'Attempt to write to undeclared property Latte\Extensions\CachingIterator::$undeclared.');

Assert::exception(function () {
	$iterator = new CachingIterator([]);
	$val = $iterator->undeclared;
}, LogicException::class, 'Attempt to read undeclared property Latte\Extensions\CachingIterator::$undeclared.');
