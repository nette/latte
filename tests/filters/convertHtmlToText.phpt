<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Filters::convertHtmlToText
 */

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('hello"', Filters::convertHtmlToText('<a href="#">hello&quot;</a>'));
Assert::same(' text', Filters::convertHtmlToText('<!-- comment --> text'));
Assert::same("' ' ' \"", Filters::convertHtmlToText('&apos; &#39; &#x27; &quot;'));
Assert::same('&', Filters::convertHtmlToText('&a<br>mp;')); // error: should return '&amp;'
