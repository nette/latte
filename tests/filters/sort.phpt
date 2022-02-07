<?php

/**
 * Test: Latte\Extensions\Filters::sort()
 */

declare(strict_types=1);

use Latte\Extensions\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same([1 => 10, 0 => 20, 30], Filters::sort([20, 10, 30]));
Assert::same([], Filters::sort([]));

Assert::same([2 => 30, 0 => 20, 1 => 10], Filters::sort([20, 10, 30], fn($a, $b) => $b <=> $a));
