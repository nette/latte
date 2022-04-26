<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlRawText
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlRawText(null));
Assert::same('', Filters::escapeHtmlRawText(''));
Assert::same('1', Filters::escapeHtmlRawText(1));
Assert::same('string', Filters::escapeHtmlRawText('string'));
Assert::same('< & \' " >', Filters::escapeHtmlRawText('< & \' " >'));
Assert::same('</p>', Filters::escapeHtmlRawText('</p>'));
Assert::same('foo <\/STYLE>', Filters::escapeHtmlRawText('foo </STYLE>'));
Assert::same('foo <\/script>', Filters::escapeHtmlRawText('foo </script>'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", Filters::escapeHtmlRawText("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\x22 bar", Filters::escapeHtmlRawText("foo \xE3\x80\x22 bar")); // stripped UTF
