<?php

/**
 * Test: Latte\Runtime\Filters::sort()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same([1 => 10, 0 => 20, 30], Filters::sort([20, 10, 30]));
Assert::same([], Filters::sort([]));
