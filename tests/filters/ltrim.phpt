<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::ltrim()
 */

use Latte\ContentType;
use Latte\Essential\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$info = new FilterInfo(ContentType::Text);
Assert::same('x', Filters::ltrim($info, " \t\n\r\x00\x0B\u{A0}x"));
Assert::same('a b ', Filters::ltrim($info, ' a b '));
Assert::same(' a b ', Filters::ltrim($info, ' a b ', ''));
Assert::same('e-', Filters::ltrim($info, "\u{158}e-", "\u{158}-")); // Ře-

Assert::exception(
	fn() => Filters::ltrim($info, "\xC2x\xA0"),
	Latte\RuntimeException::class,
	null,
);
