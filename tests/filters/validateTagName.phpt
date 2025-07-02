<?php

/**
 * Test: Latte\Runtime\AttributeHandler::validateTagName()
 */

declare(strict_types=1);

use Latte\Runtime\AttributeHandler;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('foo', AttributeHandler::validateTagName('foo'));
Assert::same('foo:bar', AttributeHandler::validateTagName('foo:bar'));

Assert::exception(
	fn() => AttributeHandler::validateTagName(null),
	Latte\RuntimeException::class,
	'Tag name must be string, null given',
);

Assert::exception(
	fn() => AttributeHandler::validateTagName(''),
	Latte\RuntimeException::class,
	"Invalid tag name ''",
);

Assert::exception(
	fn() => AttributeHandler::validateTagName('0'),
	Latte\RuntimeException::class,
	"Invalid tag name '0'",
);

Assert::exception(
	fn() => AttributeHandler::validateTagName(':foo'),
	Latte\RuntimeException::class,
	"Invalid tag name ':foo'",
);

Assert::exception(
	fn() => AttributeHandler::validateTagName('Script'),
	Latte\RuntimeException::class,
	'Forbidden variable tag name <Script>',
);

Assert::noError(
	fn() => AttributeHandler::validateTagName('Script', xml: true),
);
