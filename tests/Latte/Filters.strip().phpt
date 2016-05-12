<?php

/**
 * Test: Latte\Runtime\Filters::strip()
 */

use Latte\Runtime\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

$info = new FilterInfo('html');
Assert::same('', Filters::strip($info, ''));

Assert::same('', Filters::strip($info, "\r\n "));

Assert::same('<p> Hello </p>', Filters::strip($info, "<p> Hello </p>\r\n "));
