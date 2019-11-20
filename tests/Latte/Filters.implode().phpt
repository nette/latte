<?php

/**
 * Test: Latte\Runtime\Filters::implode()
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
	Assert::same('', Filters::implode([], ''));
	Assert::same('a,b', Filters::implode(['a', 'b'], ','));
});
