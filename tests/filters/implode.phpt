<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::implode()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	Assert::same('', Filters::implode([], ''));
	Assert::same('a,b', Filters::implode(['a', 'b'], ','));
});
