<?php

/**
 * Test: Latte\Runtime\Helpers::escapeCss
 */

declare(strict_types=1);

use Latte\Runtime\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Helpers::escapeCss(null));
Assert::same('', Helpers::escapeCss(''));
Assert::same('1', Helpers::escapeCss(1));
Assert::same('string', Helpers::escapeCss('string'));
Assert::same('\<br\>', Helpers::escapeCss(new Latte\Runtime\Html('<br>')));
Assert::same('\!\"\#\$\%\&\\\'\(\)\*\+\,\.\/\:\;\<\=\>\?\@\[\\\\\]\^\`\{\|\}\~', Helpers::escapeCss('!"#$%&\'()*+,./:;<=>?@[\]^`{|}~'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", Helpers::escapeCss("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\\\x22 bar", Helpers::escapeCss("foo \xE3\x80\x22 bar")); // stripped UTF
