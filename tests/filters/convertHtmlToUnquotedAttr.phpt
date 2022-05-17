<?php

/**
 * Test: Latte\Runtime\Filters::convertHtmlToUnquotedAttr
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('""', Filters::convertHtmlToUnquotedAttr(''));
Assert::same('"string"', Filters::convertHtmlToUnquotedAttr('string'));
Assert::same('"&lt; &amp; &apos; &quot; &gt;"', Filters::convertHtmlToUnquotedAttr('< & \' " >'));
Assert::same('"&quot;"', Filters::convertHtmlToUnquotedAttr('&quot;'));

// invalid UTF-8
Assert::same("\"foo \u{FFFD} bar\"", Filters::convertHtmlToUnquotedAttr("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("\"foo \u{FFFD}&quot; bar\"", Filters::convertHtmlToUnquotedAttr("foo \xE3\x80\x22 bar")); // stripped UTF

// JS
Assert::same('"hello &#123; worlds }"', Filters::convertHtmlToUnquotedAttr('hello { worlds }'));
