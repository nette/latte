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
	Assert::same('title=""', HtmlHelpers::formatAttribute('title', ''));

	// special values
	Assert::same('title', HtmlHelpers::formatAttribute('title', true, compat: true));
	Assert::null(HtmlHelpers::formatAttribute('title', false, compat: true));
	Assert::null(HtmlHelpers::formatAttribute('title', null, compat: true));
	Assert::same('title=""', HtmlHelpers::formatAttribute('title', null, compat: false));
	Assert::same('title="1"', HtmlHelpers::formatAttribute('title', 1));
	Assert::same('title="0"', HtmlHelpers::formatAttribute('title', 0));

	// invalid
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('title', true),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('title', false),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('title', []),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('title', (object) []),
		E_USER_WARNING,
	);
});


test('boolean attributes', function () {
	Assert::same('disabled', HtmlHelpers::formatAttribute('disabled', true, compat: true));
	Assert::same('disabled', HtmlHelpers::formatAttribute('disabled', true, compat: false));
	Assert::null(HtmlHelpers::formatAttribute('disabled', false, compat: true));
	Assert::null(HtmlHelpers::formatAttribute('disabled', false, compat: false));
	Assert::null(HtmlHelpers::formatAttribute('disabled', null, compat: true));
	Assert::null(HtmlHelpers::formatAttribute('disabled', null, compat: false));

	// special values
	Assert::null(HtmlHelpers::formatAttribute('disabled', ''));
	Assert::same('disabled', HtmlHelpers::formatAttribute('disabled', 'foo'));
	Assert::same('disabled', HtmlHelpers::formatAttribute('disabled', 1));
	Assert::null(HtmlHelpers::formatAttribute('disabled', 0));
	Assert::null(HtmlHelpers::formatAttribute('disabled', []));
	Assert::same('disabled', HtmlHelpers::formatAttribute('disabled', (object) []));
});


test('style attribute', function () {
	Assert::same('style=""', HtmlHelpers::formatAttribute('style', ''));
	Assert::same('style="color:red;"', HtmlHelpers::formatAttribute('style', 'color:red;'));
	Assert::null(HtmlHelpers::formatAttribute('style', []));

	Assert::same(
		'style="color:red;font-size:16px"',
		HtmlHelpers::formatAttribute('style', ['color' => 'red', 'font-size' => '16px']),
	);
	Assert::same(
		'style="color: red;font-size: 16px"',
		HtmlHelpers::formatAttribute('style', ['color: red', 'font-size: 16px']),
	);

	// special values
	Assert::null(HtmlHelpers::formatAttribute('style', null, compat: true));
	Assert::null(HtmlHelpers::formatAttribute('style', null, compat: false));

	// invalid
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('style', true), // independently of compat
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('style', false), // independently of compat
		E_USER_WARNING,
	);
	Assert::same(
		'style="color:1"',
		HtmlHelpers::formatAttribute('style', ['color' => true]),
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('style', (object) []),
		E_USER_WARNING,
		"StdClass value in 'style' attribute is not supported.",
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('style', 1)),
		E_USER_WARNING,
		"Int value in 'style' attribute is not supported.",
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('style', 0)),
		E_USER_WARNING,
		"Int value in 'style' attribute is not supported.",
	);
});


test('class attribute', function () {
	Assert::same('class=""', HtmlHelpers::formatAttribute('class', ''));
	Assert::same('class="btn btn-primary"', HtmlHelpers::formatAttribute('class', 'btn btn-primary'));
	Assert::null(HtmlHelpers::formatAttribute('class', []));

	Assert::same(
		'class="btn red"',
		HtmlHelpers::formatAttribute('class', ['btn', false, 'red', '']),
	);
	Assert::same(
		'class="btn"',
		HtmlHelpers::formatAttribute('class', ['btn' => true, 'red' => false]),
	);

	// special values
	Assert::null(HtmlHelpers::formatAttribute('class', null, compat: true));
	Assert::same('class=""', HtmlHelpers::formatAttribute('class', null, compat: false)); // TODO
	Assert::same('class="1"', HtmlHelpers::formatAttribute('class', 1));
	Assert::same('class="0"', HtmlHelpers::formatAttribute('class', 0));

	// invalid
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('class', true),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('class', false),
		E_USER_WARNING,
	);
	Assert::same(
		'class="a b"',
		HtmlHelpers::formatAttribute('class', ['btn' => 'a', 'red' => 'b']),
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('class', (object) []),
		E_USER_WARNING,
		"StdClass value in 'class' attribute is not supported.",
	);
});


test('edge cases', function () {
	Assert::same('foo="NAN"', HtmlHelpers::formatAttribute('foo', NAN));

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
