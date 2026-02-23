<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::firstLower
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Assert::same('hello', Filters::firstLower('Hello'));
Assert::same('český', Filters::firstLower('Český'));
Assert::same('aBC', Filters::firstLower('ABC'));
Assert::same('', Filters::firstLower(''));
