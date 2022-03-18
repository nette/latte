<?php

/**
 * Test: Latte\Essential\Filters::batch()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::type(Generator::class, Filters::batch([], 1));

Assert::same(
	[],
	iterator_to_array(Filters::batch([], -1)),
);
Assert::same(
	[],
	iterator_to_array(Filters::batch([], 0)),
);
Assert::same(
	[],
	iterator_to_array(Filters::batch([], 1)),
);
Assert::same(
	[],
	iterator_to_array(Filters::batch([], 2)),
);

Assert::same(
	[],
	iterator_to_array(Filters::batch([], -1, 'fill')),
);
Assert::same(
	[],
	iterator_to_array(Filters::batch([], 0, 'fill')),
);
Assert::same(
	[],
	iterator_to_array(Filters::batch([], 1, 'fill')),
);
Assert::same(
	[],
	iterator_to_array(Filters::batch([], 2, 'fill')),
);

Assert::same(
	[['a']],
	iterator_to_array(Filters::batch(['a'], -1)),
);
Assert::same(
	[['a']],
	iterator_to_array(Filters::batch(['a'], 0)),
);
Assert::same(
	[['a']],
	iterator_to_array(Filters::batch(['a'], 1)),
);
Assert::same(
	[['a']],
	iterator_to_array(Filters::batch(['a'], 2)),
);

Assert::same(
	[['a']],
	iterator_to_array(Filters::batch(['a'], -1, 'fill')),
);
Assert::same(
	[['a']],
	iterator_to_array(Filters::batch(['a'], 0, 'fill')),
);
Assert::same(
	[['a']],
	iterator_to_array(Filters::batch(['a'], 1, 'fill')),
);
Assert::same(
	[['a', 'fill']],
	iterator_to_array(Filters::batch(['a'], 2, 'fill')),
);

Assert::same(
	[['a'], [1 => 'b'], [2 => 'c']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 0)),
);
Assert::same(
	[['a'], [1 => 'b'], [2 => 'c']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 1)),
);
Assert::same(
	[['a', 'b'], [2 => 'c']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 2)),
);
Assert::same(
	[['a', 'b', 'c']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 3)),
);
Assert::same(
	[['a', 'b', 'c']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 4)),
);

Assert::same(
	[['a'], [1 => 'b'], [2 => 'c']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 0, 'fill')),
);
Assert::same(
	[['a'], [1 => 'b'], [2 => 'c']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 1, 'fill')),
);
Assert::same(
	[['a', 'b'], [2 => 'c', 'fill']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 2, 'fill')),
);
Assert::same(
	[['a', 'b', 'c']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 3, 'fill')),
);
Assert::same(
	[['a', 'b', 'c', 'fill']],
	iterator_to_array(Filters::batch(['a', 'b', 'c'], 4, 'fill')),
);

Assert::same(
	[['a' => 'a', 'b' => 'b'], ['c' => 'c']],
	iterator_to_array(Filters::batch(['a' => 'a', 'b' => 'b', 'c' => 'c'], 2)),
);
Assert::same(
	[['a' => 'a', 'b' => 'b'], ['c' => 'c', 0 => 'fill']],
	iterator_to_array(Filters::batch(['a' => 'a', 'b' => 'b', 'c' => 'c'], 2, 'fill')),
);
