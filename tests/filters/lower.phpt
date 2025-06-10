<?php

/**
 * Test: Latte\Essential\Filters::lower
 * @phpExtension mbstring
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Assert::same('hello', Filters::lower('HELLO'));
Assert::same('český', Filters::lower('ČESKÝ'));
Assert::same('abc123', Filters::lower('ABC123'));
Assert::same('', Filters::lower(''));
