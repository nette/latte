<?php

/**
 * Test: Latte\Runtime\Filters::implode()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('', function () {
	Assert::same('', Filters::implode([], ''));
	Assert::same('a,b', Filters::implode(['a', 'b'], ','));
});
