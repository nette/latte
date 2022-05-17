<?php

/**
 * Test: Latte\Essential\Filters::even()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::true(Filters::even(0));
Assert::false(Filters::even(1));
Assert::false(Filters::even(-1));
