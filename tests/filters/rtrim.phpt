<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::rtrim()
 */

use Latte\ContentType;
use Latte\Essential\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$info = new FilterInfo(ContentType::Text);
Assert::same('x', Filters::rtrim($info, "x \t\n\r\x00\x0B\u{A0}"));
Assert::same(' a b', Filters::rtrim($info, ' a b '));
Assert::same(' a b ', Filters::rtrim($info, ' a b ', ''));
Assert::same('e', Filters::rtrim($info, "e\u{158}-", "\u{158}-")); // eŘ-

Assert::exception(
	fn() => Filters::rtrim($info, "\xC2x\xA0"),
	Latte\RuntimeException::class,
	null,
);
