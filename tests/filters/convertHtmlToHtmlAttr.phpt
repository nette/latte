<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Filters::convertHtmlToHtmlAttr
 */

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::convertHtmlToHtmlAttr(''));
Assert::same('string', Filters::convertHtmlToHtmlAttr('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', Filters::convertHtmlToHtmlAttr('< & \' " >'));
Assert::same('hello', Filters::convertHtmlToHtmlAttr('<span>hello</span>'));
Assert::same('&quot;', Filters::convertHtmlToHtmlAttr('&quot;'));
Assert::same('&amp;', Filters::convertHtmlToHtmlAttr('&a<br>mp;')); // error: should return '&amp;amp;'

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", Filters::convertHtmlToHtmlAttr("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", Filters::convertHtmlToHtmlAttr("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello &#123; worlds }', Filters::convertHtmlToHtmlAttr('hello { worlds }'));
