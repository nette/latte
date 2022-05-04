<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlAttr
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlAttr(null));
Assert::same('', Filters::escapeHtmlAttr(''));
Assert::same('1', Filters::escapeHtmlAttr(1));
Assert::same('string', Filters::escapeHtmlAttr('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', Filters::escapeHtmlAttr('< & \' " >'));
Assert::same('&amp;quot;', Filters::escapeHtmlAttr('&quot;'));
Assert::same('&lt;br&gt; &quot;', Filters::escapeHtmlAttr(new Latte\Runtime\Html('<br> &quot;')));

// mXSS
Assert::same('`hello ', Filters::escapeHtmlAttr('`hello'));
Assert::same('`hello&quot;', Filters::escapeHtmlAttr('`hello"'));
Assert::same('`hello&apos;', Filters::escapeHtmlAttr("`hello'"));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", Filters::escapeHtmlAttr("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", Filters::escapeHtmlAttr("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello &#123; worlds }', Filters::escapeHtmlAttr('hello { worlds }'));
