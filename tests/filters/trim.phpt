<?php

/**
 * Test: Latte\Essential\Filters::trim()
 */

declare(strict_types=1);

use Latte\ContentType;
use Latte\Essential\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$info = new FilterInfo(ContentType::Text);
Assert::same('x', Filters::trim($info, " \t\n\r\x00\x0B\u{A0}x"));
Assert::same('a b', Filters::trim($info, ' a b '));
Assert::same(' a b ', Filters::trim($info, ' a b ', ''));
Assert::same('e', Filters::trim($info, "\u{158}e-", "\u{158}-")); // Ře-

Assert::exception(
	fn() => Filters::trim($info, "\xC2x\xA0"),
	Latte\RuntimeException::class,
	null,
);
