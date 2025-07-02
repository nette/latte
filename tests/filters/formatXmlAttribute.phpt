<?php

declare(strict_types=1);

use Latte\Runtime\AttributeHandler;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('skipped attributes', function () {
	Assert::null(AttributeHandler::formatXmlAttribute('title', false));
	Assert::null(AttributeHandler::formatXmlAttribute('placeholder', null));
});


test('regular text attributes', function () {
	Assert::same(
		'title="Hello &amp; Welcome"',
		AttributeHandler::formatXmlAttribute('title', 'Hello & Welcome'),
	);
	Assert::same(
		'title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
		AttributeHandler::formatXmlAttribute('title', '"Hello" & \'Welcome\''),
	);

	Assert::same(
		'placeholder=""',
		AttributeHandler::formatXmlAttribute('placeholder', ''),
	);
});


test('boolean attributes', function () {
	Assert::same(
		'disabled="disabled"',
		AttributeHandler::formatXmlAttribute('disabled', true),
	);
});


test('special values (numbers, Infinity, NaN)', function () {
	Assert::same(
		'width="0"',
		AttributeHandler::formatXmlAttribute('width', 0),
	);
	Assert::same(
		'foo="NAN"',
		AttributeHandler::formatXmlAttribute('foo', NAN),
	);

	// invalid UTF-8
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \u{FFFD} bar\"",
		AttributeHandler::formatXmlAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a=\"foo \u{FFFD}&quot; bar\"",
		AttributeHandler::formatXmlAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});


test('invalid values', function () {
	Assert::error(
		fn() => Assert::null(AttributeHandler::formatXmlAttribute('foo', [])),
		E_USER_WARNING,
		"Array value in 'foo' attribute is not supported.",
	);

	Assert::error(
		fn() => Assert::null(AttributeHandler::formatXmlAttribute('foo', (object) [])),
		E_USER_WARNING,
		"StdClass value in 'foo' attribute is not supported.",
	);
});
