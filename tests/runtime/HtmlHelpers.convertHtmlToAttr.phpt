<?php

/**
 * Test: Latte\Runtime\HtmlHelpers::convertHtmlToAttr
 */

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::convertHtmlToAttr(''));
Assert::same('string', HtmlHelpers::convertHtmlToAttr('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', HtmlHelpers::convertHtmlToAttr('< & \' " >'));
Assert::same('hello', HtmlHelpers::convertHtmlToAttr('<span>hello</span>'));
Assert::same('&quot;', HtmlHelpers::convertHtmlToAttr('&quot;'));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", HtmlHelpers::convertHtmlToAttr("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", HtmlHelpers::convertHtmlToAttr("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello &#123; worlds }', HtmlHelpers::convertHtmlToAttr('hello { worlds }'));
