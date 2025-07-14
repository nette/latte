<?php

declare(strict_types=1);

use Latte\Runtime\XmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::noError(fn() => XmlHelpers::validateAttributeName('_name'));
Assert::noError(fn() => XmlHelpers::validateAttributeName('元素')); // Chinese for "element"
Assert::noError(fn() => XmlHelpers::validateAttributeName(':my-XML_element.name:2'));

Assert::exception(fn() => XmlHelpers::validateAttributeName(''), Latte\RuntimeException::class);
Assert::exception(fn() => XmlHelpers::validateAttributeName("name\n"), Latte\RuntimeException::class);
Assert::exception(fn() => XmlHelpers::validateAttributeName('1name'), Latte\RuntimeException::class);
Assert::exception(fn() => XmlHelpers::validateAttributeName('-name'), Latte\RuntimeException::class);
Assert::exception(fn() => XmlHelpers::validateAttributeName('name name'), Latte\RuntimeException::class);
Assert::exception(fn() => XmlHelpers::validateAttributeName('name&name'), Latte\RuntimeException::class);
Assert::exception(fn() => XmlHelpers::validateAttributeName('name"name'), Latte\RuntimeException::class);
