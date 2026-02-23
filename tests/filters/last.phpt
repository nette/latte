<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::last()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('arrays', function () {
	Assert::same(null, Filters::last([]));
	Assert::same('a', Filters::last(['a']));
	Assert::same('b', Filters::last(['a', 'b']));
	Assert::same('b', Filters::last([2 => 'a', 1 => 'b']));
});

test('strings', function () {
	Assert::same('', Filters::last(''));
	Assert::same('a', Filters::last('a'));
	Assert::same('b', Filters::last('ab'));
	Assert::same('ý', Filters::last('žý'));
});
