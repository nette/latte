<?php

/**
 * Test: Latte\Runtime\HtmlHelpers::convertHtmlToRawText
 */

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::convertHtmlToRawText(''));
Assert::same('string', HtmlHelpers::convertHtmlToRawText('string'));
Assert::same('< & \' " >', HtmlHelpers::convertHtmlToRawText('< & \' " >'));
Assert::same('<style> </style>', HtmlHelpers::convertHtmlToRawText('<style> </style>'));
Assert::same('<x-script> </x-script>', HtmlHelpers::convertHtmlToRawText('<script> </script>'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", HtmlHelpers::convertHtmlToRawText("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\x22 bar", HtmlHelpers::convertHtmlToRawText("foo \xE3\x80\x22 bar")); // stripped UTF
