<?php

/**
 * Test: Latte\Runtime\Filters::escapeHtmlComment
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeHtmlComment(null));
Assert::same('', Filters::escapeHtmlComment(''));
Assert::same('1', Filters::escapeHtmlComment(1));
Assert::same('string', Filters::escapeHtmlComment('string'));
Assert::same('< & \' " >', Filters::escapeHtmlComment('< & \' " >'));
Assert::same('&quot;', Filters::escapeHtmlComment('&quot;'));
Assert::same('<br>', Filters::escapeHtmlComment(new Latte\Runtime\Html('<br>')));
Assert::same(' - ', Filters::escapeHtmlComment('-'));
Assert::same(' - - ', Filters::escapeHtmlComment('--'));
Assert::same(' - - - ', Filters::escapeHtmlComment('---'));
Assert::same(' >', Filters::escapeHtmlComment('>'));
Assert::same(' !', Filters::escapeHtmlComment('!'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", Filters::escapeHtmlComment("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\x22 bar", Filters::escapeHtmlComment("foo \xE3\x80\x22 bar")); // stripped UTF
