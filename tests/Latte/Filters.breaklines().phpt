<?php

/**
 * Test: Latte\Runtime\Filters::breakLines()
 */

use Latte\Runtime\Filters;
use Latte\Runtime\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$input = "Hello\nmy\r\nfriend\n\r";

Filters::$xhtml = TRUE;
Assert::equal(new Html("Hello<br />\nmy<br />\r\nfriend<br />\n\r"), Filters::breakLines($input));

Filters::$xhtml = FALSE;
Assert::equal(new Html("Hello<br>\nmy<br>\r\nfriend<br>\n\r"), Filters::breakLines($input));

Assert::equal(new Html("&lt;&gt;<br>\n&amp;"), Filters::breakLines("<>\n&"));

// Html is ignored
Assert::equal(new Html("&lt;&gt;<br>\n&amp;"), Filters::breakLines(new Html("<>\n&")));
