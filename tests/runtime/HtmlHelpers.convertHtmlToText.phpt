<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\HtmlHelpers::convertHtmlToText
 */

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('hello"', HtmlHelpers::convertHtmlToText('<a href="#">hello&quot;</a>'));
Assert::same(' text', HtmlHelpers::convertHtmlToText('<!-- comment --> text'));
Assert::same("' ' ' \"", HtmlHelpers::convertHtmlToText('&apos; &#39; &#x27; &quot;'));
Assert::same('&', HtmlHelpers::convertHtmlToText('&a<br>mp;')); // error: should return '&amp;'
