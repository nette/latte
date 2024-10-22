<?php

/**
 * Test: Latte\Essential\Filters::filter()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// arrays
Assert::same(
	['a' => 1, 'b' => 2],
	iterator_to_array(Filters::filter(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v) => $v < 3,
	)),
);

Assert::same(
	['c' => 3],
	iterator_to_array(Filters::filter(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v, $k) => $k === 'c',
	)),
);

Assert::same(
	['a' => 1, 'b' => 2, 'c' => 3],
	iterator_to_array(Filters::filter(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v, $k, $a) => $a === ['a' => 1, 'b' => 2, 'c' => 3],
	)),
);

Assert::same(
	[],
	iterator_to_array(Filters::filter([], fn() => true)),
);


// iterators
Assert::same(
	['a' => 1, 'b' => 2],
	iterator_to_array(Filters::filter(
		new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]),
		fn($v) => $v < 3,
	)),
);

Assert::same(
	['c' => 3],
	iterator_to_array(Filters::filter(
		new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]),
		fn($v, $k) => $k === 'c',
	)),
);

Assert::same(
	['a' => 1, 'b' => 2, 'c' => 3],
	iterator_to_array(Filters::filter(
		$it = new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]),
		fn($v, $k, $a) => $a === $it,
	)),
);

Assert::same(
	[],
	iterator_to_array(Filters::filter(
		new ArrayIterator([]),
		fn() => true,
	)),
);
