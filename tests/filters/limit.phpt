<?php declare(strict_types=1);

/**
 * Test: |limit filter
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function limit(string|iterable $value, int $length): string|array|\Generator
{
	return Latte\Essential\Filters::slice($value, 0, $length, preserveKeys: true);
}


test('arrays', function () {
	Assert::same([], limit([], 5));
	Assert::same([0 => 'a', 1 => 'b'], limit(['a', 'b', 'c', 'd'], 2));
	Assert::same([0 => 'a', 1 => 'b', 2 => 'c', 3 => 'd'], limit(['a', 'b', 'c', 'd'], 99));
	Assert::same([], limit(['a', 'b', 'c'], 0));
});


test('arrays preserve keys', function () {
	$arr = ['a', 'b', 10 => 'c', 'd'];
	Assert::same([0 => 'a', 1 => 'b'], limit($arr, 2));
	Assert::same([0 => 'a', 1 => 'b', 10 => 'c'], limit($arr, 3));
});


test('iterators', function () {
	$gen = function () {
		yield 'a' => 1;
		yield 'b' => 2;
		yield 'c' => 3;
		yield 'd' => 4;
	};

	Assert::same(['a' => 1, 'b' => 2], iterator_to_array(limit($gen(), 2)));
	Assert::same(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4], iterator_to_array(limit($gen(), 99)));
	Assert::same([], iterator_to_array(limit($gen(), 0)));
});


test('strings (UTF-8)', function () {
	Assert::same('Příl', limit('Příliš', 4));
	Assert::same('Příliš', limit('Příliš', 99));
	Assert::same('', limit('Příliš', 0));
	Assert::same('', limit('', 5));
	Assert::same('ř', limit('řeč', 1));
});


test('via Latte', function () {
	$latte = createLatte();
	Assert::same('ab', $latte->renderToString('{$s|limit:2}', ['s' => 'abcdef']));
	Assert::same('Př', $latte->renderToString('{$s|limit:2}', ['s' => 'Příliš']));
	Assert::same('a, b', $latte->renderToString('{$arr|limit:2|implode:", "}', ['arr' => ['a', 'b', 'c']]));
});
