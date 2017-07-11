<?php

/**
 * Test: Latte\Runtime\Filters::trim()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Runtime\FilterInfo;
use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$info = new FilterInfo(Engine::CONTENT_TEXT);
Assert::same('x', Filters::trim($info, " \t\n\r\x00\x0B\u{A0}x"));
Assert::same('a b', Filters::trim($info, ' a b '));
Assert::same(' a b ', Filters::trim($info, ' a b ', ''));
Assert::same('e', Filters::trim($info, "\u{158}e-", "\u{158}-")); // Ře-

Assert::exception(function () use ($info) {
	Filters::trim($info, "\xC2x\xA0");
}, Latte\RegexpException::class, null);
