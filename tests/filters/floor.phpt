<?php

/**
 * Test: Latte\Runtime\Filters::floor()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same(3.0, Filters::floor(3.4));
Assert::same(3.0, Filters::floor(3.5));
Assert::same(135.79, Filters::floor(135.79, 3));
Assert::same(135.7, Filters::floor(135.79, 1));
