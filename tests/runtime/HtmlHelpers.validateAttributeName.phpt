<?php declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::noError(fn() => HtmlHelpers::validateAttributeName('_name'));
Assert::noError(fn() => HtmlHelpers::validateAttributeName('42name'));
Assert::noError(fn() => HtmlHelpers::validateAttributeName('元素')); // Chinese for "element"
Assert::noError(fn() => HtmlHelpers::validateAttributeName('-my&HTML_element.name:2'));

Assert::exception(fn() => HtmlHelpers::validateAttributeName(''), Latte\RuntimeException::class);
Assert::exception(fn() => HtmlHelpers::validateAttributeName("name\n"), Latte\RuntimeException::class);
Assert::exception(fn() => HtmlHelpers::validateAttributeName('name name'), Latte\RuntimeException::class);
Assert::exception(fn() => HtmlHelpers::validateAttributeName('name"name'), Latte\RuntimeException::class);
