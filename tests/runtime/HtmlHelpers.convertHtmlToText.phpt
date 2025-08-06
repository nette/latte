<?php

/**
 * Test: Latte\Runtime\HtmlHelpers::convertHtmlToText
 */

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('hello"', HtmlHelpers::convertHtmlToText('<a href="#">hello&quot;</a>'));
Assert::same(' text', HtmlHelpers::convertHtmlToText('<!-- comment --> text'));
Assert::same("' ' ' \"", HtmlHelpers::convertHtmlToText('&apos; &#39; &#x27; &quot;'));
