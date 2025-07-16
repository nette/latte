<?php

/**
 * Test: CachingIterator basic usage.
 */

declare(strict_types=1);

use Latte\Essential\CachingIterator;
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
