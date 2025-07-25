<?php

declare(strict_types=1);

use Latte\Runtime\XmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('skipped attributes', function () {
	Assert::null(XmlHelpers::formatAttribute('title', false));
	Assert::null(XmlHelpers::formatAttribute('placeholder', null));
});


test('regular text attributes', function () {
	Assert::same(
		'title="Hello &amp; Welcome"',
		XmlHelpers::formatAttribute('title', 'Hello & Welcome'),
	);
	Assert::same(
		'title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
		XmlHelpers::formatAttribute('title', '"Hello" & \'Welcome\''),
	);

	Assert::same(
		'placeholder=""',
		XmlHelpers::formatAttribute('placeholder', ''),
	);
});


test('boolean attributes', function () {
	Assert::same(
		'disabled="disabled"',
		XmlHelpers::formatAttribute('disabled', true),
	);
});


test('special values (numbers, Infinity, NaN)', function () {
	Assert::same(
		'width="0"',
		XmlHelpers::formatAttribute('width', 0),
	);
	Assert::same(
		'foo="NAN"',
		XmlHelpers::formatAttribute('foo', NAN),
	);

	// invalid UTF-8
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \u{FFFD} bar\"",
		XmlHelpers::formatAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a=\"foo \u{FFFD}&quot; bar\"",
		XmlHelpers::formatAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});


test('invalid values', function () {
	Assert::error(
		fn() => Assert::null(XmlHelpers::formatAttribute('foo', [])),
		E_USER_WARNING,
		"Array value in 'foo' attribute is not supported.",
	);

	Assert::error(
		fn() => Assert::null(XmlHelpers::formatAttribute('foo', (object) [])),
		E_USER_WARNING,
		"StdClass value in 'foo' attribute is not supported.",
	);
});
