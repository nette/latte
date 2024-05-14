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
	yield ['a' => 55] => ['k' => 22];
	yield ['a' => 66] => (object) ['k' => 11];
	yield ['a' => 77] => ['k' => 33];
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
	Assert::same([1 => 11, 0 => 22, 33], Filters::sort([22, 11, 33]));
	Assert::same([], Filters::sort([]));
});


test('iterator', function () {
	$sorted = Filters::sort(iterator());

	Assert::same(3, count($sorted));
	Assert::equal(
		[
			[['a' => 55], ['k' => 22]],
			[['a' => 77], ['k' => 33]],
			[['a' => 66], (object) ['k' => 11]],
		],
		exportIterator($sorted),
	);
});


test('re-iteration', function () {
	$sorted = Filters::sort(iterator());
	$res = [
		[['a' => 55], ['k' => 22]],
		[['a' => 77], ['k' => 33]],
		[['a' => 66], (object) ['k' => 11]],
	];
	Assert::equal(
		$res,
		exportIterator($sorted),
	);
	Assert::equal(
		$res,
		exportIterator($sorted),
	);
});


test('user comparison + array', function () {
	Assert::same(
		[2 => 33, 0 => 22, 1 => 11],
		Filters::sort([22, 11, 33], fn($a, $b) => $b <=> $a)
	);
});


test('user comparison + iterator', function () {
	Assert::equal(
		[
			[['a' => 66], (object) ['k' => 11]],
			[['a' => 77], ['k' => 33]],
			[['a' => 55], ['k' => 22]],
		],
		exportIterator(Filters::sort(iterator(), fn($a, $b) => $b <=> $a)),
	);
});


test('array + by', function () {
	Assert::equal(
		[1 => (object) ['k' => 11], 0 => ['k' => 22], ['k' => 33]],
		Filters::sort([['k' => 22], (object) ['k' => 11], ['k' => 33]], by: 'k'),
	);
	Assert::same([], Filters::sort([], by: 'k'));
});


test('iterator + by', function () {
	Assert::equal(
		[
			[['a' => 66], (object) ['k' => 11]],
			[['a' => 55], ['k' => 22]],
			[['a' => 77], ['k' => 33]],
		],
		exportIterator(Filters::sort(iterator(), by: 'k')),
	);
});


test('callback + array + by', function () {
	Assert::same(
		[1 => 11, 0 => 22, 33],
		Filters::sort([22, 11, 33], by: fn($a) => $a * 11)
	);
});


test('callback + iterator + by', function () {
	Assert::equal(
		[
			[['a' => 77], ['k' => 33]],
			[['a' => 55], ['k' => 22]],
			[['a' => 66], (object) ['k' => 11]],
		],
		exportIterator(Filters::sort(iterator(), by: fn($a) => -((array) $a)['k'])),
	);
});


test('array + byKey', function () {
	Assert::same([1 => 11, 0 => 22, 33], Filters::sort([22, 11, 33]));
	Assert::same([], Filters::sort([], byKey: true));
});


test('iterator + byKey', function () {
	Assert::equal(
		[
			[['a' => 55], ['k' => 22]],
			[['a' => 66], (object) ['k' => 11]],
			[['a' => 77], ['k' => 33]],
		],
		exportIterator(Filters::sort(iterator(), byKey: true)),
	);
});


test('user comparison + array + byKey', function () {
	Assert::same(
		[2 => 33, 1 => 11, 0 => 22],
		Filters::sort([22, 11, 33], fn($a, $b) => $b <=> $a, byKey: true),
	);
});


test('user comparison + iterator + byKey', function () {
	Assert::equal(
		[
			[['a' => 77], ['k' => 33]],
			[['a' => 66], (object) ['k' => 11]],
			[['a' => 55], ['k' => 22]],
		],
		exportIterator(Filters::sort(iterator(), fn($a, $b) => $b <=> $a, byKey: true)),
	);
});


test('iterator + by + byKey', function () {
	Assert::equal(
		[
			[['a' => 55], ['k' => 22]],
			[['a' => 66], (object) ['k' => 11]],
			[['a' => 77], ['k' => 33]],
		],
		exportIterator(Filters::sort(iterator(), byKey: 'a')),
	);
});


test('callback + iterator + by + byKey', function () {
	Assert::equal(
		[
			[['a' => 77], ['k' => 33]],
			[['a' => 66], (object) ['k' => 11]],
			[['a' => 55], ['k' => 22]],
		],
		exportIterator(Filters::sort(iterator(), byKey: fn($a) => -((array) $a)['a'])),
	);
});
