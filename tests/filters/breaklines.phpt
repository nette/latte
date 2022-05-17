<?php

/**
 * Test: Latte\Essential\Filters::breaklines()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Latte\Runtime;
use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$input = "Hello\nmy\r\nfriend\n\r";

Runtime\Filters::$xml = true;
Assert::equal(new Html("Hello<br />\nmy<br />\r\nfriend<br />\n\r"), Filters::breaklines($input));

Runtime\Filters::$xml = false;
Assert::equal(new Html("Hello<br>\nmy<br>\r\nfriend<br>\n\r"), Filters::breaklines($input));

Assert::equal(new Html("&lt;&gt;<br>\n&amp;"), Filters::breaklines("<>\n&"));

// Html is ignored
Assert::equal(new Html("&lt;&gt;<br>\n&amp;"), Filters::breaklines(new Html("<>\n&")));
