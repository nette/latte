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
	Assert::true($iterator->isFirst());
	Assert::false($iterator->isLast());
	Assert::same(1, $iterator->getCounter());
	Assert::same(0, $iterator->getCounter0());
	Assert::same('1', (string) $iterator);

	$iterator->next();
	Assert::true($iterator->valid());
	Assert::false($iterator->isFirst());
	Assert::true($iterator->isLast());
	Assert::same(2, $iterator->getCounter());
	Assert::same(1, $iterator->getCounter0());

	$iterator->next();
	Assert::false($iterator->valid());

	$iterator->rewind();
	Assert::true($iterator->isFirst());
	Assert::false($iterator->isLast());
	Assert::same(1, $iterator->getCounter());
	Assert::same(0, $iterator->getCounter0());
	Assert::false($iterator->isEmpty());
});


test('', function () {
	$arr = ['Nette'];

	$iterator = new CachingIterator($arr);
	$iterator->rewind();
	Assert::true($iterator->valid());
	Assert::true($iterator->isFirst());
	Assert::true($iterator->isLast());
	Assert::same(1, $iterator->getCounter());
	Assert::same(0, $iterator->getCounter0());

	$iterator->next();
	Assert::false($iterator->valid());

	$iterator->rewind();
	Assert::true($iterator->isFirst());
	Assert::true($iterator->isLast());
	Assert::same(1, $iterator->getCounter());
	Assert::same(0, $iterator->getCounter0());
	Assert::false($iterator->isEmpty());
});


test('', function () {
	$arr = [];

	$iterator = new CachingIterator($arr);
	$iterator->next();
	$iterator->next();
	Assert::false($iterator->isFirst());
	Assert::true($iterator->isLast());
	Assert::same(0, $iterator->getCounter());
	Assert::same(0, $iterator->getCounter0());
	Assert::true($iterator->isEmpty());
});

test('Check if next position is valid', function () {
	// empty iterator
	$inner = new class implements Iterator {
		#[ReturnTypeWillChange]
		public function current()
		{
			throw new RuntimeException('Invalid state');
		}


		public function next(): void
		{
		}


		#[ReturnTypeWillChange]
		public function key()
		{
			throw new RuntimeException('Invalid state');
		}


		public function valid(): bool
		{
			return false;
		}


		public function rewind(): void
		{
		}
	};

	$iterator = new CachingIterator($inner);
	$iterator->rewind();
	Assert::null($iterator->nextKey);
	Assert::null($iterator->nextValue);
});
