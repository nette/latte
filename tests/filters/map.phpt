<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::map()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// arrays
Assert::same(
	['a' => 10, 'b' => 20, 'c' => 30],
	iterator_to_array(Filters::map(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v) => $v * 10,
	)),
);

Assert::same(
	['a' => 'a1', 'b' => 'b2', 'c' => 'c3'],
	iterator_to_array(Filters::map(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v, $k) => $k . $v,
	)),
);

Assert::same(
	['a' => true, 'b' => true, 'c' => true],
	iterator_to_array(Filters::map(
		['a' => 1, 'b' => 2, 'c' => 3],
		fn($v, $k, $a) => $a === ['a' => 1, 'b' => 2, 'c' => 3],
	)),
);

Assert::same(
	[],
	iterator_to_array(Filters::map([], fn($v) => $v)),
);


// iterators
Assert::same(
	['a' => 10, 'b' => 20, 'c' => 30],
	iterator_to_array(Filters::map(
		new ArrayIterator(['a' => 1, 'b' => 2, 'c' => 3]),
		fn($v) => $v * 10,
	)),
);


// in template
$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
Assert::same(
	'2',
	$latte->renderToString('{=[1, 2, 3]|map: fn($x) => $x * 2|first}'),
);
