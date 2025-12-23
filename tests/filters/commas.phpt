<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::commas()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('joins array with comma and space by default', function () {
	Assert::same('one, two, three', Filters::commas(['one', 'two', 'three']));
	Assert::same('1, 2, 3', Filters::commas(['1', '2', '3']));
	Assert::same('apple, banana, orange', Filters::commas(['apple', 'banana', 'orange']));
});


test('joins single element', function () {
	Assert::same('one', Filters::commas(['one']));
});


test('joins empty array', function () {
	Assert::same('', Filters::commas([]));
});


test('works with numeric values', function () {
	Assert::same('1, 2, 3', Filters::commas([1, 2, 3]));
	Assert::same('1.5, 2.5, 3.5', Filters::commas([1.5, 2.5, 3.5]));
});


test('uses custom separator for last pair', function () {
	Assert::same('one, two and three', Filters::commas(['one', 'two', 'three'], ' and '));
	Assert::same('one and two', Filters::commas(['one', 'two'], ' and '));
	Assert::same('apple, banana, or orange', Filters::commas(['apple', 'banana', 'orange'], ', or '));
	Assert::same('red, green & blue', Filters::commas(['red', 'green', 'blue'], ' & '));
});


test('custom last separator with single element', function () {
	Assert::same('one', Filters::commas(['one'], ' and '));
});


test('custom last separator with empty array', function () {
	Assert::same('', Filters::commas([], ' and '));
});
