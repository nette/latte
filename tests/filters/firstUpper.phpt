<?php

/**
 * Test: Latte\Essential\Filters::firstUpper
 * @phpExtension mbstring
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Assert::same('Hello', Filters::firstUpper('hello'));
Assert::same('Český', Filters::firstUpper('český'));
Assert::same('Abc', Filters::firstUpper('abc'));
Assert::same('', Filters::firstUpper(''));
