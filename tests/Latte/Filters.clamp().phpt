<?php

/**
 * Test: Latte\Runtime\Filters::clamp()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(20, Filters::clamp(20, 10, 30));
Assert::same(21, Filters::clamp(20, 21, 30));
Assert::same(19, Filters::clamp(20, 10, 19));
Assert::same(19.0, Filters::clamp(20.0, 10.0, 19.0));

Assert::exception(function () {
	Filters::clamp(20, 30, 10);
}, InvalidArgumentException::class, 'Minimum (30) is not less than maximum (10).');
