<?php

/**
 * Test: Latte\Runtime\Filters::breaklines()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Latte\Runtime\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$input = "Hello\nmy\r\nfriend\n\r";

Filters::$xhtml = true;
Assert::equal(new Html("Hello<br />\nmy<br />\r\nfriend<br />\n\r"), Filters::breaklines($input));

Filters::$xhtml = false;
Assert::equal(new Html("Hello<br>\nmy<br>\r\nfriend<br>\n\r"), Filters::breaklines($input));

Assert::equal(new Html("&lt;&gt;<br>\n&amp;"), Filters::breaklines("<>\n&"));

// Html is ignored
Assert::equal(new Html("&lt;&gt;<br>\n&amp;"), Filters::breaklines(new Html("<>\n&")));
