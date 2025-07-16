<?php

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('skipped attributes', function () {
	Assert::null(HtmlHelpers::formatAttribute('title', false));
	Assert::null(HtmlHelpers::formatAttribute('placeholder', null));
});


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
	Assert::same(
		'readonly="0"',
		HtmlHelpers::formatAttribute('readonly', 0),
	);
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
		'style="color"',
		HtmlHelpers::formatAttribute('style', ['color' => true]),
	);

	Assert::same(
		'style="1"',
		HtmlHelpers::formatAttribute('style', 1),
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

	// invalid
	Assert::same(
		'class="btn:a red:b"',
		HtmlHelpers::formatAttribute('class', ['btn' => 'a', 'red' => 'b']),
	);
});


test('special values (numbers, Infinity, NaN)', function () {
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
		Error::class,
	);
});
