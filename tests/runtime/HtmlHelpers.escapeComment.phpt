<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\HtmlHelpers::escapeComment
 */

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', HtmlHelpers::escapeComment(null));
Assert::same('', HtmlHelpers::escapeComment(''));
Assert::same('1', HtmlHelpers::escapeComment(1));
Assert::same('string', HtmlHelpers::escapeComment('string'));
Assert::same('< & \' " >', HtmlHelpers::escapeComment('< & \' " >'));
Assert::same('&quot;', HtmlHelpers::escapeComment('&quot;'));
Assert::same('<br>', HtmlHelpers::escapeComment(new Latte\Runtime\Html('<br>')));
Assert::same(' - ', HtmlHelpers::escapeComment('-'));
Assert::same(' - - ', HtmlHelpers::escapeComment('--'));
Assert::same(' - - - ', HtmlHelpers::escapeComment('---'));
Assert::same(' >', HtmlHelpers::escapeComment('>'));
Assert::same(' !', HtmlHelpers::escapeComment('!'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", HtmlHelpers::escapeComment("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\x22 bar", HtmlHelpers::escapeComment("foo \xE3\x80\x22 bar")); // stripped UTF
