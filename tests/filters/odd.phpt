<?php

/**
 * Test: Latte\Essential\Filters::odd()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::false(Filters::odd(0));
Assert::true(Filters::odd(1));
Assert::true(Filters::odd(-1));
