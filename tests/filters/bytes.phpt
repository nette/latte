<?php

/**
 * Test: Latte\Runtime\Filters::bytes()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('0 B', Filters::bytes(0.1));


Assert::same('-1.03 GB', Filters::bytes(-1024 * 1024 * 1050));


Assert::same('8881.78 PB', Filters::bytes(1e19));
