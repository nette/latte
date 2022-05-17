<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlTag
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('""', Filters::escapeHtmlTag(null));
Assert::same('""', Filters::escapeHtmlTag(''));
Assert::same('1', Filters::escapeHtmlTag(1));
Assert::same('string', Filters::escapeHtmlTag('string'));
Assert::same('N:string-string', Filters::escapeHtmlTag('N:string-string'));
Assert::same('"&lt; &amp; &apos; &quot; &gt;"', Filters::escapeHtmlTag('< & \' " >'));
Assert::same('"&amp;quot;"', Filters::escapeHtmlTag('&quot;'));
Assert::same('"&lt;br&gt;"', Filters::escapeHtmlTag(new Latte\Runtime\Html('<br>')));
Assert::same('"`hello "', Filters::escapeHtmlTag('`hello'));

// invalid UTF-8
Assert::same("\"foo \u{FFFD} bar\"", Filters::escapeHtmlTag("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("\"foo \u{FFFD}&quot; bar\"", Filters::escapeHtmlTag("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('"hello &#123; worlds }"', Filters::escapeHtmlTag('hello { worlds }'));
