<?php

/**
 * Test: Latte\Essential\Filters::sort()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function iterator(): Generator
{
	yield 'a' => 20;
	yield 'b' => 10;
	yield [true] => 30;
}


function exportIterator(Traversable $iterator): array
{
	$res = [];
	foreach ($iterator as $key => $value) {
		$res[] = [$key, $value];
	}
	return $res;
}


test('array', function () {
	Assert::same([1 => 10, 0 => 20, 30], Filters::sort([20, 10, 30]));
	Assert::same([], Filters::sort([]));
});


test('iterator', function () {
	Assert::same(
		[['b', 10], ['a', 20], [[true], 30]],
		exportIterator(Filters::sort(iterator())),
	);
});


test('re-iteration', function () {
	$sorted = Filters::sort(iterator());

	Assert::same(
		[['b', 10], ['a', 20], [[true], 30]],
		exportIterator($sorted),
	);

	Assert::same(
		[['b', 10], ['a', 20], [[true], 30]],
		exportIterator($sorted),
	);
});


test('user comparison + array', function () {
	Assert::same(
		[2 => 30, 0 => 20, 1 => 10],
		Filters::sort([20, 10, 30], fn($a, $b) => $b <=> $a)
	);
});


test('user comparison + iterator', function () {
	Assert::same(
		[[[true], 30], ['a', 20], ['b', 10]],
		exportIterator(Filters::sort(iterator(), fn($a, $b) => $b <=> $a)),
	);
});
