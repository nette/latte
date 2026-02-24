<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::capitalize
 * @phpExtension mbstring
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Assert::same('Hello', Filters::capitalize('hello'));
Assert::same('Hello World', Filters::capitalize('hello world'));
Assert::same('Český Český', Filters::capitalize('český český'));
Assert::same('123Abc', Filters::capitalize('123abc'));
Assert::same('', Filters::capitalize(''));
