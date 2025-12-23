<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::column()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$data = [
	['id' => 1, 'name' => 'John', 'age' => 30],
	['id' => 2, 'name' => 'Jane', 'age' => 25],
	['id' => 3, 'name' => 'Bob', 'age' => 35],
];

test('extracts single column', function () use ($data) {
	Assert::same(['John', 'Jane', 'Bob'], Filters::column($data, 'name'));
	Assert::same([1, 2, 3], Filters::column($data, 'id'));
	Assert::same([30, 25, 35], Filters::column($data, 'age'));
});


test('extracts column with custom index', function () use ($data) {
	Assert::same([1 => 'John', 2 => 'Jane', 3 => 'Bob'], Filters::column($data, 'name', 'id'));
	Assert::same(['John' => 30, 'Jane' => 25, 'Bob' => 35], Filters::column($data, 'age', 'name'));
});


test('extracts all values when column is null', function () use ($data) {
	Assert::same([
		['id' => 1, 'name' => 'John', 'age' => 30],
		['id' => 2, 'name' => 'Jane', 'age' => 25],
		['id' => 3, 'name' => 'Bob', 'age' => 35],
	], Filters::column($data, null));
});


test('works with iterable', function () {
	$iterator = new ArrayIterator([
		['id' => 1, 'name' => 'Alice'],
		['id' => 2, 'name' => 'Bob'],
	]);

	Assert::same(['Alice', 'Bob'], Filters::column($iterator, 'name'));
});


test('works with numeric keys', function () {
	$data = [
		[10, 'John', 30],
		[20, 'Jane', 25],
		[30, 'Bob', 35],
	];

	Assert::same(['John', 'Jane', 'Bob'], Filters::column($data, 1));
	Assert::same([10, 20, 30], Filters::column($data, 0));
});
