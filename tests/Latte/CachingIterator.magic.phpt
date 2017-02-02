<?php

/**
 * Test: Latte\Runtime\CachingIterator basic usage.
 */

declare(strict_types=1);

use Latte\Runtime\CachingIterator;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () { // ==> Two items in array
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
}, 'LogicException', 'Call to undefined method Latte\Runtime\CachingIterator::undeclared().');

Assert::exception(function () {
	$iterator = new CachingIterator([]);
	$iterator->rewnd();
}, 'LogicException', 'Call to undefined method Latte\Runtime\CachingIterator::rewnd(), did you mean rewind()?');

Assert::exception(function () {
	$iterator = new CachingIterator([]);
	$iterator->undeclared = 'value';
}, 'LogicException', 'Attempt to write to undeclared property Latte\Runtime\CachingIterator::$undeclared.');

Assert::exception(function () {
	$iterator = new CachingIterator([]);
	$val = $iterator->undeclared;
}, 'LogicException', 'Attempt to read undeclared property Latte\Runtime\CachingIterator::$undeclared.');
