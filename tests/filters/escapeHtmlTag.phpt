<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlTag
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlTag(null));
Assert::same('', Filters::escapeHtmlTag(''));
Assert::same('1', Filters::escapeHtmlTag(1));
Assert::same('string', Filters::escapeHtmlTag('string'));
Assert::same('žluťoučký', Filters::escapeHtmlTag('žluťoučký'));
Assert::same('&lt;&#32;&amp;&#32;&apos;&#32;&quot;&#32;&#61;&#32;&#47;&#32;&gt;', Filters::escapeHtmlTag('< & \' " = / >'));
Assert::same('&amp;quot;', Filters::escapeHtmlTag('&quot;'));
Assert::same('&lt;br&gt;', Filters::escapeHtmlTag(new Latte\Runtime\Html('<br>')));

// invalid UTF-8
Assert::same("foo&#32;\u{FFFD}&#32;bar", Filters::escapeHtmlTag("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo&#32;\u{FFFD}&quot;&#32;bar", Filters::escapeHtmlTag("foo \xE3\x80\x22 bar")); // stripped UTF
