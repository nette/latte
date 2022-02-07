<?php

/**
 * Test: Latte\Extensions\Filters::odd()
 */

declare(strict_types=1);

use Latte\Extensions\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::false(Filters::odd(0));
Assert::true(Filters::odd(1));
Assert::true(Filters::odd(-1));
