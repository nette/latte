<?php

/**
 * Test: Latte\Runtime\Filters::escapeCss
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeCss(null));
Assert::same('', Filters::escapeCss(''));
Assert::same('1', Filters::escapeCss(1));
Assert::same('string', Filters::escapeCss('string'));
Assert::same('\<br\>', Filters::escapeCss(new Latte\Runtime\Html('<br>')));
Assert::same('\!\"\#\$\%\&\\\'\(\)\*\+\,\.\/\:\;\<\=\>\?\@\[\\\\\]\^\`\{\|\}\~', Filters::escapeCss('!"#$%&\'()*+,./:;<=>?@[\]^`{|}~'));

// invalid UTF-8
Assert::same("foo \u{D800} bar", Filters::escapeCss("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\\\x22 bar", Filters::escapeCss("foo \xE3\x80\x22 bar")); // stripped UTF
