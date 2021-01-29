<?php

/**
 * Test: Latte\Runtime\Filters::ceil()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(4.0, Filters::ceil(3.4));
Assert::same(4.0, Filters::ceil(3.5));
Assert::same(135.22, Filters::ceil(135.22, 3));
Assert::same(135.3, Filters::ceil(135.22, 1));
