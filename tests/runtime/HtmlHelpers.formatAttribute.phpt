<?php

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('regular text attributes', function () {
	Assert::same(
		'title="Hello &amp; Welcome"',
		HtmlHelpers::formatAttribute('title', 'Hello & Welcome'),
	);
	Assert::same(
		'title=\'"Hello" &amp; &apos;Welcome&apos;\'',
		HtmlHelpers::formatAttribute('title', '"Hello" & \'Welcome\''),
	);

	Assert::same(
		'placeholder=""',
		HtmlHelpers::formatAttribute('placeholder', ''),
	);
});


test('boolean attributes', function () {
	Assert::same(
		'disabled',
		HtmlHelpers::formatAttribute('disabled', true),
	);
	Assert::null(HtmlHelpers::formatAttribute('disabled', false));
	Assert::null(HtmlHelpers::formatAttribute('required', null));
	Assert::null(HtmlHelpers::formatAttribute('readonly', 0));
});


test('style attribute', function () {
	Assert::same(
		'style="color:red;"',
		HtmlHelpers::formatAttribute('style', 'color:red;'),
	);
	Assert::null(HtmlHelpers::formatAttribute('style', []));

	Assert::same(
		'style="color:red;font-size:16px"',
		HtmlHelpers::formatAttribute('style', ['color' => 'red', 'font-size' => '16px']),
	);
	Assert::same(
		'style="color: red;font-size: 16px"',
		HtmlHelpers::formatAttribute('style', ['color: red', 'font-size: 16px']),
	);

	// invalid
	Assert::same(
		'style="color:1"',
		HtmlHelpers::formatAttribute('style', ['color' => true]),
	);

	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('style', 1)),
		E_USER_WARNING,
		"Int value in 'style' attribute is not supported.",
	);
});


test('class attribute', function () {
	Assert::same(
		'class="btn btn-primary"',
		HtmlHelpers::formatAttribute('class', 'btn btn-primary'),
	);
	Assert::null(HtmlHelpers::formatAttribute('class', []));

	Assert::same(
		'class="btn red"',
		HtmlHelpers::formatAttribute('class', ['btn', false, 'red', '']),
	);
	Assert::same(
		'class="btn"',
		HtmlHelpers::formatAttribute('class', ['btn' => true, 'red' => false]),
	);

	Assert::same(
		'class="a b"',
		HtmlHelpers::formatAttribute('class', ['btn' => 'a', 'red' => 'b']),
	);
});


test('data attributes', function () {
	Assert::same('data-foo="bar"', HtmlHelpers::formatAttribute('data-foo', 'bar'));
	Assert::same('data-foo="0"', HtmlHelpers::formatAttribute('data-foo', 0));
	Assert::same('data-foo', HtmlHelpers::formatAttribute('data-foo', null));

	Assert::same(
		'data-json=\'{"user":"Karel","age":30,"spec":"&amp;<>\"&apos;\""}\'',
		HtmlHelpers::formatAttribute('data-json', ['user' => 'Karel', 'age' => 30, 'spec' => '&<>"\'"']),
	);
});


test('special values (numbers, Infinity, NaN)', function () {
	Assert::same(
		'placeholder=""',
		HtmlHelpers::formatAttribute('placeholder', null),
	);
	Assert::same(
		'width="0"',
		HtmlHelpers::formatAttribute('width', 0),
	);
	Assert::same(
		'foo="NAN"',
		HtmlHelpers::formatAttribute('foo', NAN),
	);

	// invalid UTF-8 (is not processed)
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \u{D800} bar\"",
		HtmlHelpers::formatAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a='foo \xE3\x80\x22 bar'",
		HtmlHelpers::formatAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});


test('invalid values', function () {
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('foo', (object) [])),
		E_USER_WARNING,
		"StdClass value in 'foo' attribute is not supported.",
	);

	Assert::error(
		fn() => Assert::same('title=""', HtmlHelpers::formatAttribute('title', false)),
		E_USER_WARNING,
		"Bool value in 'title' attribute is not supported.",
	);
});
