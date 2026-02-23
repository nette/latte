<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\HtmlHelpers::validateTagChange()
 */

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('foo', HtmlHelpers::validateTagChange('foo'));
Assert::same('foo:bar', HtmlHelpers::validateTagChange('foo:bar'));

Assert::exception(
	fn() => HtmlHelpers::validateTagChange(null),
	Latte\RuntimeException::class,
	'Tag name must be string, null given',
);

Assert::exception(
	fn() => HtmlHelpers::validateTagChange(''),
	Latte\RuntimeException::class,
	"Invalid tag name ''",
);

Assert::exception(
	fn() => HtmlHelpers::validateTagChange('0'),
	Latte\RuntimeException::class,
	"Invalid tag name '0'",
);

Assert::exception(
	fn() => HtmlHelpers::validateTagChange(':foo'),
	Latte\RuntimeException::class,
	"Invalid tag name ':foo'",
);

Assert::exception(
	fn() => HtmlHelpers::validateTagChange('Script'),
	Latte\RuntimeException::class,
	'Forbidden: Cannot change element to <Script>',
);
