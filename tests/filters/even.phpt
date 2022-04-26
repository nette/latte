<?php

/**
 * Test: Latte\Runtime\Filters::even()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::true(Filters::even(0));
Assert::false(Filters::even(1));
Assert::false(Filters::even(-1));
