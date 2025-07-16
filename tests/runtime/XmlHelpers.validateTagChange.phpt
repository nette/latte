<?php

/**
 * Test: Latte\Runtime\XmlHelpers::validateTagChange()
 */

declare(strict_types=1);

use Latte\Runtime\XmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('foo', XmlHelpers::validateTagChange('foo'));
Assert::same('foo:bar', XmlHelpers::validateTagChange('foo:bar'));
Assert::same(':bar', XmlHelpers::validateTagChange(':bar'));

Assert::exception(
	fn() => XmlHelpers::validateTagChange(null),
	Latte\RuntimeException::class,
	'Tag name must be string, null given',
);

Assert::exception(
	fn() => XmlHelpers::validateTagChange(''),
	Latte\RuntimeException::class,
	"Invalid tag name ''",
);

Assert::exception(
	fn() => XmlHelpers::validateTagChange('0'),
	Latte\RuntimeException::class,
	"Invalid tag name '0'",
);
