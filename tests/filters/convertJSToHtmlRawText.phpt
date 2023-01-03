<?php

/**
 * Test: Latte\Runtime\Filters::convertJSToHtmlRawText
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::convertJSToHtmlRawText(null));
Assert::same('', Filters::convertJSToHtmlRawText(''));
Assert::same('1', Filters::convertJSToHtmlRawText(1));
Assert::same('string', Filters::convertJSToHtmlRawText('string'));
Assert::same('< & \' " >', Filters::convertJSToHtmlRawText('< & \' " >'));
Assert::same('</p>', Filters::convertJSToHtmlRawText('</p>'));
Assert::same('foo <\/STYLE>', Filters::convertJSToHtmlRawText('foo </STYLE>'));
Assert::same('foo <\/script>', Filters::convertJSToHtmlRawText('foo </script>'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", Filters::convertJSToHtmlRawText("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\x22 bar", Filters::convertJSToHtmlRawText("foo \xE3\x80\x22 bar")); // stripped UTF
