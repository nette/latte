<?php

/**
 * Test: Latte\Runtime\Filters::convertHtmlToHtmlRawText
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::convertHtmlToHtmlRawText(''));
Assert::same('string', Filters::convertHtmlToHtmlRawText('string'));
Assert::same('< & \' " >', Filters::convertHtmlToHtmlRawText('< & \' " >'));
Assert::same('</p>', Filters::convertHtmlToHtmlRawText('</p>'));
Assert::same('foo </STYLE>', Filters::convertHtmlToHtmlRawText('foo </STYLE>'));
Assert::same('foo &lt;script>', Filters::convertHtmlToHtmlRawText('foo <script>'));
Assert::same('foo &lt;/script>', Filters::convertHtmlToHtmlRawText('foo </script>'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", Filters::convertHtmlToHtmlRawText("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\x22 bar", Filters::convertHtmlToHtmlRawText("foo \xE3\x80\x22 bar")); // stripped UTF
