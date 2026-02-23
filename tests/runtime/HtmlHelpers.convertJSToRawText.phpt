<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\HtmlHelpers::convertJSToRawText
 */

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::convertJSToRawText(null));
Assert::same('', HtmlHelpers::convertJSToRawText(''));
Assert::same('1', HtmlHelpers::convertJSToRawText(1));
Assert::same('string', HtmlHelpers::convertJSToRawText('string'));
Assert::same('< & \' " >', HtmlHelpers::convertJSToRawText('< & \' " >'));
Assert::same('</p>', HtmlHelpers::convertJSToRawText('</p>'));
Assert::same('foo <\/STYLE>', HtmlHelpers::convertJSToRawText('foo </STYLE>'));
Assert::same('foo <\/script>', HtmlHelpers::convertJSToRawText('foo </script>'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", HtmlHelpers::convertJSToRawText("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\x22 bar", HtmlHelpers::convertJSToRawText("foo \xE3\x80\x22 bar")); // stripped UTF
