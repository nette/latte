<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::slice()
 */

use Latte\Essential\Filters;
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


test('iterators', function () {
	$gen = function () {
		yield 'a' => 1;
		yield 'b' => 2;
		yield 'c' => 3;
		yield 'd' => 4;
		yield 'e' => 5;
	};

	Assert::type(Generator::class, Filters::slice($gen(), 0));
	Assert::same([], iterator_to_array(Filters::slice($gen(), 0, 0)));
	Assert::same([1, 2, 3], iterator_to_array(Filters::slice($gen(), 0, 3)));
	Assert::same([1, 2, 3, 4, 5], iterator_to_array(Filters::slice($gen(), 0)));
	Assert::same([1, 2, 3, 4, 5], iterator_to_array(Filters::slice($gen(), 0, 99)));
	Assert::same([2, 3, 4, 5], iterator_to_array(Filters::slice($gen(), 1)));
	Assert::same([3, 4, 5], iterator_to_array(Filters::slice($gen(), 2)));
	Assert::same([3, 4], iterator_to_array(Filters::slice($gen(), 2, 2)));
	Assert::same([], iterator_to_array(Filters::slice($gen(), 5, 1)));
});


test('iterators & preserveKeys', function () {
	$gen = function () {
		yield 'a' => 1;
		yield 'b' => 2;
		yield 'c' => 3;
		yield 'd' => 4;
		yield 'e' => 5;
	};

	Assert::same(['a' => 1, 'b' => 2, 'c' => 3], iterator_to_array(Filters::slice($gen(), 0, 3, preserveKeys: true)));
	Assert::same(['c' => 3, 'd' => 4], iterator_to_array(Filters::slice($gen(), 2, 2, preserveKeys: true)));
	Assert::same(['b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], iterator_to_array(Filters::slice($gen(), 1, null, preserveKeys: true)));
});


test('limit (slice alias with preserveKeys)', function () {
	$limit = fn(iterable $value, int $length, int $offset = 0) => Filters::slice($value, $offset, $length, preserveKeys: true);

	$arr = ['a', 'b', 10 => 'd', 'e'];

	Assert::same([0 => 'a', 1 => 'b'], $limit($arr, 2));
	Assert::same([10 => 'd', 11 => 'e'], $limit($arr, 2, 2));
	Assert::same($arr, $limit($arr, 99));
	Assert::same([], $limit($arr, 0));

	$gen = function () {
		yield 'a' => 1;
		yield 'b' => 2;
		yield 'c' => 3;
		yield 'd' => 4;
		yield 'e' => 5;
	};

	Assert::same(['a' => 1, 'b' => 2, 'c' => 3], iterator_to_array($limit($gen(), 3)));
	Assert::same(['c' => 3, 'd' => 4], iterator_to_array($limit($gen(), 2, 2)));
	Assert::same([], iterator_to_array($limit($gen(), 0)));
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
