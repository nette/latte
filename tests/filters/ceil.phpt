<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::ceil()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same(4.0, Filters::ceil(3.4));
Assert::same(4.0, Filters::ceil(3.5));
Assert::same(135.22, Filters::ceil(135.22, 3));
Assert::same(135.3, Filters::ceil(135.22, 1));
