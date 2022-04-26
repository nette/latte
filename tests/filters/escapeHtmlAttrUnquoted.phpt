<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlAttrUnquoted
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('""', Filters::escapeHtmlAttrUnquoted(null));
Assert::same('""', Filters::escapeHtmlAttrUnquoted(''));
Assert::same('1', Filters::escapeHtmlAttrUnquoted(1));
Assert::same('string', Filters::escapeHtmlAttrUnquoted('string'));
Assert::same('N:string-string', Filters::escapeHtmlAttrUnquoted('N:string-string'));
Assert::same('"&lt; &amp; &apos; &quot; &gt;"', Filters::escapeHtmlAttrUnquoted('< & \' " >'));
Assert::same('"&amp;quot;"', Filters::escapeHtmlAttrUnquoted('&quot;'));
Assert::same('"&lt;br&gt;"', Filters::escapeHtmlAttrUnquoted(new Latte\Runtime\Html('<br>')));
Assert::same('"`hello "', Filters::escapeHtmlAttrUnquoted('`hello'));

// invalid UTF-8
Assert::same("\"foo \u{FFFD} bar\"", Filters::escapeHtmlAttrUnquoted("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("\"foo \u{FFFD}&quot; bar\"", Filters::escapeHtmlAttrUnquoted("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('"hello &#123; worlds }"', Filters::escapeHtmlAttrUnquoted('hello { worlds }'));
