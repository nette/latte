<?php

/**
 * Test: Latte\Runtime\Filters::trim()
 */

use Latte\Engine;
use Latte\Runtime\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$info = new FilterInfo(Engine::CONTENT_TEXT);
Assert::same('x',  Filters::trim($info, " \t\n\r\x00\x0B\xC2\xA0x"));
Assert::same('a b',  Filters::trim($info, ' a b '));
Assert::same(' a b ',  Filters::trim($info, ' a b ', ''));
Assert::same('e',  Filters::trim($info, "\xc5\x98e-", "\xc5\x98-")); // Ře-

Assert::exception(function () use ($info) {
	Filters::trim($info, "\xC2x\xA0");
}, 'Latte\RegexpException', NULL);
