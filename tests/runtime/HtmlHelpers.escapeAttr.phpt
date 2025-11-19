<?php

/**
 * Test: Latte\Runtime\HtmlHelpers::escapeAttr
 */

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::escapeAttr(null));
Assert::same('', HtmlHelpers::escapeAttr(''));
Assert::same('1', HtmlHelpers::escapeAttr(1));
Assert::same('string', HtmlHelpers::escapeAttr('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', HtmlHelpers::escapeAttr('< & \' " >'));
Assert::same('&amp;quot;', HtmlHelpers::escapeAttr('&quot;'));
Assert::same(' &quot;', HtmlHelpers::escapeAttr(new Latte\Runtime\Html('<br> &quot;')));

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", HtmlHelpers::escapeAttr("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", HtmlHelpers::escapeAttr("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('hello &#123; worlds }', HtmlHelpers::escapeAttr('hello { worlds }'));
