<?php

/**
 * Test: Latte\Runtime\Filters::safeTag()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('foo', Filters::safeTag('foo'));
Assert::same('foo:bar', Filters::safeTag('foo:bar'));

Assert::exception(
	fn() => Filters::safeTag(null),
	Latte\RuntimeException::class,
	'Tag name must be string, null given',
);

Assert::exception(
	fn() => Filters::safeTag(''),
	Latte\RuntimeException::class,
	"Invalid tag name ''",
);

Assert::exception(
	fn() => Filters::safeTag('0'),
	Latte\RuntimeException::class,
	"Invalid tag name '0'",
);

Assert::exception(
	fn() => Filters::safeTag(':foo'),
	Latte\RuntimeException::class,
	"Invalid tag name ':foo'",
);

Assert::exception(
	fn() => Filters::safeTag('Script'),
	Latte\RuntimeException::class,
	'Forbidden variable tag name <Script>',
);

Assert::noError(
	fn() => Filters::safeTag('Script', xml: true),
);
