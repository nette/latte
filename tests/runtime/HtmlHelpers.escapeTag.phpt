<?php

/**
 * Test: Latte\Runtime\HtmlHelpers::escapeTag
 */

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::escapeTag(null));
Assert::same('', HtmlHelpers::escapeTag(''));
Assert::same('1', HtmlHelpers::escapeTag(1));
Assert::same('string', HtmlHelpers::escapeTag('string'));
Assert::same('žluťoučký', HtmlHelpers::escapeTag('žluťoučký'));
Assert::same('&lt;&#32;&amp;&#32;&apos;&#32;&quot;&#32;&#61;&#32;&#47;&#32;&gt;', HtmlHelpers::escapeTag('< & \' " = / >'));
Assert::same('&amp;quot;', HtmlHelpers::escapeTag('&quot;'));
Assert::same('&lt;br&gt;', HtmlHelpers::escapeTag(new Latte\Runtime\Html('<br>')));

// invalid UTF-8
Assert::same("foo&#32;\u{FFFD}&#32;bar", HtmlHelpers::escapeTag("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo&#32;\u{FFFD}&quot;&#32;bar", HtmlHelpers::escapeTag("foo \xE3\x80\x22 bar")); // stripped UTF
