<?php

/**
 * Test: Latte\Runtime\Filters::first()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('arrays', function () {
	$arr = ['a', 'b', 10 => 'd', 'e'];

	Assert::same([], Filters::slice([], 0));
	Assert::same([], Filters::slice([], -1));
	Assert::same([], Filters::slice([], 1));
	Assert::same(['e'], Filters::slice($arr, -1));
	Assert::same(['a', 'b', 'd', 'e'], Filters::slice($arr, 0));
	Assert::same(['a', 'b', 'd', 'e'], Filters::slice($arr, 0, 99));
	Assert::same(['b', 'd', 'e'], Filters::slice($arr, 1));
	Assert::same([], Filters::slice($arr, 4, 1));
	Assert::same(['a', 'b'], Filters::slice($arr, 0, -2));
});


test('arrays & preserveKeys', function () {
	$arr = ['a', 'b', 10 => 'd', 'e'];

	Assert::same([], Filters::slice([], 0, null, true));
	Assert::same([], Filters::slice([], -1, null, true));
	Assert::same([], Filters::slice([], 1, null, true));
	Assert::same([11 => 'e'], Filters::slice($arr, -1, null, true));
	Assert::same($arr, Filters::slice($arr, 0, null, true));
	Assert::same($arr, Filters::slice($arr, 0, 99, true));
	Assert::same([1 => 'b', 10 => 'd', 'e'], Filters::slice($arr, 1, null, true));
	Assert::same([], Filters::slice($arr, 4, 1, true));
	Assert::same(['a', 'b'], Filters::slice($arr, 0, -2, true));
});


test('strings', function () {
	$s = "\u{158}ekn\u{11B}te, jak se (dnes) m\u{E1}te?"; // Řekněte, jak se (dnes) máte?

	Assert::same('', Filters::slice('', 0));
	Assert::same('', Filters::slice('', -1));
	Assert::same('', Filters::slice('', 1));
	Assert::same('?', Filters::slice($s, -1));
	Assert::same('Řekněte, jak se (dnes) máte?', Filters::slice($s, 0));
	Assert::same('Řekněte, jak se (dnes) máte?', Filters::slice($s, 0, 99));
	Assert::same('ekněte, jak se (dnes) máte?', Filters::slice($s, 1));
	Assert::same('ě', Filters::slice($s, 4, 1));
	Assert::same('ěte, jak se (d', Filters::slice($s, 4, -10));
});
