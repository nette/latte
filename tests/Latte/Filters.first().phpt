<?php

/**
 * Test: Latte\Runtime\Filters::first()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('arrays', function () {
	Assert::same(null, Filters::first([]));
	Assert::same('a', Filters::first(['a']));
	Assert::same('a', Filters::first(['a', 'b']));
	Assert::same('a', Filters::first([2 => 'a', 1 => 'b']));
});

test('strings', function () {
	Assert::same('', Filters::first(''));
	Assert::same('a', Filters::first('a'));
	Assert::same('a', Filters::first('ab'));
	Assert::same('ž', Filters::first('žý'));
});
