<?php

/**
 * Test: Latte\Essential\Filters::divisibleBy()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::true(Filters::divisibleBy(0, 1));

Assert::true(Filters::divisibleBy(10, 1));

Assert::true(Filters::divisibleBy(-10, 10));

Assert::false(Filters::divisibleBy(10, 20));

Assert::exception(
	fn() => Filters::divisibleBy(10, 0),
	DivisionByZeroError::class,
	'Modulo by zero',
);
