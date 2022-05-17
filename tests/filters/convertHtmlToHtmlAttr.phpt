<?php

/**
 * Test: Latte\Runtime\Filters::convertHtmlToHtmlAttr
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::convertHtmlToHtmlAttr(''));
Assert::same('string', Filters::convertHtmlToHtmlAttr('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', Filters::convertHtmlToHtmlAttr('< & \' " >'));
Assert::same('&quot;', Filters::convertHtmlToHtmlAttr('&quot;'));

// mXSS
Assert::same('`hello ', Filters::convertHtmlToHtmlAttr('`hello'));
Assert::same('`hello&quot;', Filters::convertHtmlToHtmlAttr('`hello"'));
Assert::same('`hello&apos;', Filters::convertHtmlToHtmlAttr("`hello'"));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", Filters::convertHtmlToHtmlAttr("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", Filters::convertHtmlToHtmlAttr("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello &#123; worlds }', Filters::convertHtmlToHtmlAttr('hello { worlds }'));
