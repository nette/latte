<?php

/**
 * Test: Latte\Runtime\Filters::trim()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('x',  Filters::trim(" \t\n\r\x00\x0B\u{A0}x"));
Assert::same('a b',  Filters::trim(' a b '));
Assert::same(' a b ',  Filters::trim(' a b ', ''));
Assert::same('e',  Filters::trim("\u{158}e-", "\u{158}-")); // Ře-

Assert::exception(function () {
	Filters::trim("\xC2x\xA0");
}, Latte\RegexpException::class, NULL);
