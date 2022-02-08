<?php

/**
 * Test: Latte\Runtime\Filters::round()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(3.0, Filters::round(3.4));
Assert::same(4.0, Filters::round(3.5));
Assert::same(135.79, Filters::round(135.79, 3));
Assert::same(135.8, Filters::round(135.79, 1));
