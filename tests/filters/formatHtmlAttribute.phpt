<?php

declare(strict_types=1);

use Latte\Runtime\AttributeHandler;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('regular text attributes', function () {
	Assert::same(
		'title="Hello &amp; Welcome"',
		AttributeHandler::formatHtmlAttribute('title', 'Hello & Welcome'),
	);
	Assert::same(
		'title=\'"Hello" &amp; &apos;Welcome&apos;\'',
		AttributeHandler::formatHtmlAttribute('title', '"Hello" & \'Welcome\''),
	);

	Assert::same(
		'placeholder=""',
		AttributeHandler::formatHtmlAttribute('placeholder', ''),
	);
});


test('boolean attributes', function () {
	Assert::same(
		'disabled',
		AttributeHandler::formatHtmlAttribute('disabled', true),
	);
	Assert::null(AttributeHandler::formatHtmlAttribute('disabled', false));
	Assert::null(AttributeHandler::formatHtmlAttribute('required', null));
	Assert::null(AttributeHandler::formatHtmlAttribute('readonly', 0));
});


test('style attribute', function () {
	Assert::same(
		'style="color:red;"',
		AttributeHandler::formatHtmlAttribute('style', 'color:red;'),
	);
	Assert::null(AttributeHandler::formatHtmlAttribute('style', []));

	Assert::same(
		'style="color:red;font-size:16px"',
		AttributeHandler::formatHtmlAttribute('style', ['color' => 'red', 'font-size' => '16px']),
	);
	Assert::same(
		'style="color: red;font-size: 16px"',
		AttributeHandler::formatHtmlAttribute('style', ['color: red', 'font-size: 16px']),
	);

	// invalid
	Assert::same(
		'style="color:1"',
		AttributeHandler::formatHtmlAttribute('style', ['color' => true]),
	);

	Assert::error(
		fn() => Assert::null(AttributeHandler::formatHtmlAttribute('style', 1)),
		E_USER_WARNING,
		"Int value in 'style' attribute is not supported.",
	);
});


test('class attribute', function () {
	Assert::same(
		'class="btn btn-primary"',
		AttributeHandler::formatHtmlAttribute('class', 'btn btn-primary'),
	);
	Assert::null(AttributeHandler::formatHtmlAttribute('class', []));

	Assert::same(
		'class="btn red"',
		AttributeHandler::formatHtmlAttribute('class', ['btn', false, 'red', '']),
	);
	Assert::same(
		'class="btn"',
		AttributeHandler::formatHtmlAttribute('class', ['btn' => true, 'red' => false]),
	);

	Assert::same(
		'class="a b"',
		AttributeHandler::formatHtmlAttribute('class', ['btn' => 'a', 'red' => 'b']),
	);
});


test('data attributes', function () {
	Assert::same('data-foo="bar"', AttributeHandler::formatHtmlAttribute('data-foo', 'bar'));
	Assert::same('data-foo="0"', AttributeHandler::formatHtmlAttribute('data-foo', 0));
	Assert::same('data-foo', AttributeHandler::formatHtmlAttribute('data-foo', null));

	Assert::same(
		'data-json=\'{"user":"Karel","age":30,"spec":"&amp;<>\"&apos;\""}\'',
		AttributeHandler::formatHtmlAttribute('data-json', ['user' => 'Karel', 'age' => 30, 'spec' => '&<>"\'"']),
	);
});


test('special values (numbers, Infinity, NaN)', function () {
	Assert::same(
		'placeholder=""',
		AttributeHandler::formatHtmlAttribute('placeholder', null),
	);
	Assert::same(
		'width="0"',
		AttributeHandler::formatHtmlAttribute('width', 0),
	);
	Assert::same(
		'foo="NAN"',
		AttributeHandler::formatHtmlAttribute('foo', NAN),
	);

	// invalid UTF-8 (is not processed)
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \u{D800} bar\"",
		AttributeHandler::formatHtmlAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a='foo \xE3\x80\x22 bar'",
		AttributeHandler::formatHtmlAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});


test('invalid values', function () {
	Assert::error(
		fn() => Assert::null(AttributeHandler::formatHtmlAttribute('foo', (object) [])),
		E_USER_WARNING,
		"StdClass value in 'foo' attribute is not supported.",
	);

	Assert::error(
		fn() => Assert::same('title=""', AttributeHandler::formatHtmlAttribute('title', false)),
		E_USER_WARNING,
		"Bool value in 'title' attribute is not supported.",
	);
});
