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
		'foo="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
		XmlHelpers::formatAttribute('foo', '"Hello" & \'Welcome\''),
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
	Assert::same(
		"a=\"foo \u{FFFD} bar\"",
		XmlHelpers::formatAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same(
		"a=\"foo \u{FFFD}&quot; bar\"",
		XmlHelpers::formatAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});


test('invalid values', function () {
	Assert::error(
		fn() => Assert::null(XmlHelpers::formatAttribute('foo', (object) [])),
		Error::class,
	);
});
