<?php

/**
 * Test: Latte\Essential\Filters::replaceRe
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Assert::same('foo bar', Filters::replaceRe('foo baz', '/baz/', 'bar'));
Assert::same('111', Filters::replaceRe('abc', '/[a-z]/', '1'));
Assert::same('yyyyyyy', Filters::replaceRe('český', '/./', 'y')); // not unicode
Assert::same('foo', Filters::replaceRe('foo', '/bar/', 'baz'));
