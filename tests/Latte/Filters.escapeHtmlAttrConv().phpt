<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlAttrConv
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlAttrConv(null));
Assert::same('', Filters::escapeHtmlAttrConv(''));
Assert::same('1', Filters::escapeHtmlAttrConv(1));
Assert::same('string', Filters::escapeHtmlAttrConv('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', Filters::escapeHtmlAttrConv('< & \' " >'));
Assert::same('&quot;', Filters::escapeHtmlAttrConv('&quot;'));
Assert::same('&lt;br&gt;', Filters::escapeHtmlAttrConv(new Latte\Runtime\Html('<br>')));

// mXSS
Assert::same('`hello ', Filters::escapeHtmlAttrConv('`hello'));
Assert::same('`hello&quot;', Filters::escapeHtmlAttrConv('`hello"'));
Assert::same('`hello&apos;', Filters::escapeHtmlAttrConv("`hello'"));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", Filters::escapeHtmlAttrConv("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", Filters::escapeHtmlAttrConv("foo \xE3\x80\x22 bar")); // stripped UTF
