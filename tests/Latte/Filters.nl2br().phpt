<?php

/**
 * Test: Latte\Runtime\Filters::nl2br()
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$input = "Hello\nmy\r\nfriend\n\r";

Filters::$xhtml = true;
Assert::same("Hello<br />\nmy<br />\r\nfriend<br />\n\r", @Filters::nl2br($input)); // @ is deprecated

Filters::$xhtml = false;
Assert::same("Hello<br>\nmy<br>\r\nfriend<br>\n\r", @Filters::nl2br($input)); // @ is deprecated
