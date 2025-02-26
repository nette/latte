<?php

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('name validity', function () {
	Assert::type('string', Filters::renderXmlAttribute('_name', ''));
	Assert::type('string', Filters::renderXmlAttribute('元素', '')); // Chinese for "element"
	Assert::type('string', Filters::renderXmlAttribute(':my-XML_element.name:2', ''));

	Assert::exception(fn() => Filters::renderXmlAttribute('', ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderXmlAttribute("name\n", ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderXmlAttribute('1name', ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderXmlAttribute('-name', ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderXmlAttribute('name name', ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderXmlAttribute('name&name', ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderXmlAttribute('name"name', ''), Latte\RuntimeException::class);
});


test('Skipped attributes', function () {
	Assert::null(Filters::renderXmlAttribute('title', false));
	Assert::null(Filters::renderXmlAttribute('placeholder', null));
});


test('Regular text attributes', function () {
	Assert::same('title="Hello &amp; Welcome"', Filters::renderXmlAttribute('title', 'Hello & Welcome'));
	Assert::same('title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"', Filters::renderXmlAttribute('title', '"Hello" & \'Welcome\''));

	Assert::same('placeholder=""', Filters::renderXmlAttribute('placeholder', ''));
});


test('boolean attributes', function () {
	Assert::same('disabled="disabled"', Filters::renderXmlAttribute('disabled', true));
});


test('special values (numbers, Infinity, NaN)', function () {
	Assert::same('width="0"', Filters::renderXmlAttribute('width', 0));
	Assert::same('foo="NAN"', Filters::renderXmlAttribute('foo', NAN));

	Assert::exception(
		fn() => Filters::renderXmlAttribute('foo', []),
		Latte\RuntimeException::class,
		'Array is not allowed as XML attribute value',
	);

	// invalid UTF-8
	Assert::same("a=\"foo \u{FFFD} bar\"", Filters::renderXmlAttribute('a', "foo \u{D800} bar")); // invalid codepoint high surrogates
	Assert::same("a=\"foo \u{FFFD}&quot; bar\"", Filters::renderXmlAttribute('a', "foo \xE3\x80\x22 bar")); // stripped UTF
});
