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
	Assert::true($iterator->isFirst());
	Assert::false($iterator->isLast());
	Assert::same(1, $iterator->getCounter());

	$iterator->next();
	Assert::true($iterator->valid());
	Assert::false($iterator->isFirst());
	Assert::true($iterator->isLast());
	Assert::same(2, $iterator->getCounter());

	$iterator->next();
	Assert::false($iterator->valid());

	$iterator->rewind();
	Assert::true($iterator->isFirst());
	Assert::false($iterator->isLast());
	Assert::same(1, $iterator->getCounter());
	Assert::false($iterator->isEmpty());
});


test(function () {
	$arr = ['Nette'];

	$iterator = new CachingIterator($arr);
	$iterator->rewind();
	Assert::true($iterator->valid());
	Assert::true($iterator->isFirst());
	Assert::true($iterator->isLast());
	Assert::same(1, $iterator->getCounter());

	$iterator->next();
	Assert::false($iterator->valid());

	$iterator->rewind();
	Assert::true($iterator->isFirst());
	Assert::true($iterator->isLast());
	Assert::same(1, $iterator->getCounter());
	Assert::false($iterator->isEmpty());
});


test(function () {
	$arr = [];

	$iterator = new CachingIterator($arr);
	$iterator->next();
	$iterator->next();
	Assert::false($iterator->isFirst());
	Assert::true($iterator->isLast());
	Assert::same(0, $iterator->getCounter());
	Assert::true($iterator->isEmpty());
});
