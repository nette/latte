<?php

declare(strict_types=1);

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Str
{
	public function __toString()
	{
		return 'one&<br>';
	}
}


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
	Assert::null(HtmlHelpers::formatAttribute('title', null));
	Assert::same('title="1"', HtmlHelpers::formatAttribute('title', 1));
	Assert::same('title="0"', HtmlHelpers::formatAttribute('title', 0));
	Assert::same('title="one&amp;"', HtmlHelpers::formatAttribute('title', new Latte\Runtime\Html('one&amp;<br>')));
	Assert::same('title="one&amp;<br>"', HtmlHelpers::formatAttribute('title', new Str));

	// invalid
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('title', true)),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('title', false)),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('title', [])),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('title', (object) [])),
		E_USER_WARNING,
	);
});


test('boolean attributes', function () {
	Assert::same('disabled', HtmlHelpers::formatAttribute('disabled', true));
	Assert::null(HtmlHelpers::formatAttribute('disabled', false));
	Assert::null(HtmlHelpers::formatAttribute('disabled', null));

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
	Assert::null(HtmlHelpers::formatAttribute('style', null));

	// invalid
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('style', true)),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('style', false)),
		E_USER_WARNING,
	);
	Assert::same(
		'style="color:1"',
		HtmlHelpers::formatAttribute('style', ['color' => true]),
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('style', (object) [])),
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


test('space-separated attribute', function () {
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
	Assert::null(HtmlHelpers::formatAttribute('class', null));
	Assert::same('class="1"', HtmlHelpers::formatAttribute('class', 1));
	Assert::same('class="0"', HtmlHelpers::formatAttribute('class', 0));

	// invalid
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('class', true)),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('class', false)),
		E_USER_WARNING,
	);
	Assert::same(
		'class="a b"',
		HtmlHelpers::formatAttribute('class', ['btn' => 'a', 'red' => 'b']),
	);
	Assert::error(
		fn() => Assert::null(HtmlHelpers::formatAttribute('class', (object) [])),
		E_USER_WARNING,
		"StdClass value in 'class' attribute is not supported.",
	);
});


test('on* attributes', function () {
	Assert::same('onclick=""', HtmlHelpers::formatAttribute('onclick', ''));
	Assert::same('onclick="bar"', HtmlHelpers::formatAttribute('onclick', 'bar'));
	Assert::null(HtmlHelpers::formatAttribute('onclick', null));

	// invalid
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('onclick', 1),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('onclick', 0),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('onclick', true),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('onclick', false),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('onclick', []),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('onclick', (object) []),
		E_USER_WARNING,
	);
});


test('data attributes', function () {
	Assert::same('data-foo=""', HtmlHelpers::formatAttribute('data-foo', ''));
	Assert::same('data-foo="bar"', HtmlHelpers::formatAttribute('data-foo', 'bar'));
	Assert::same('data-foo="1"', HtmlHelpers::formatAttribute('data-foo', 1));
	Assert::same('data-foo="0"', HtmlHelpers::formatAttribute('data-foo', 0));

	Assert::same('data-foo', HtmlHelpers::formatAttribute('data-foo', true, nAttr: true));
	Assert::null(HtmlHelpers::formatAttribute('data-foo', false, nAttr: true));
	Assert::null(HtmlHelpers::formatAttribute('data-foo', null, nAttr: true));
	Assert::same('data-foo', HtmlHelpers::formatAttribute('data-foo', null, nAttr: false));

	Assert::same('data-foo="[]"', HtmlHelpers::formatAttribute('data-foo', []));
	Assert::same('data-foo="{}"', HtmlHelpers::formatAttribute('data-foo', (object) []));
	Assert::same(
		'data-foo=\'{"user":"Karel","age":30,"spec":"&amp;<>\"&apos;\""}\'',
		HtmlHelpers::formatAttribute('data-foo', ['user' => 'Karel', 'age' => 30, 'spec' => '&<>"\'"']),
	);

	// invalid
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('data-foo', true),
		E_USER_WARNING,
	);
	Assert::error(
		fn() => HtmlHelpers::formatAttribute('data-foo', false),
		E_USER_WARNING,
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
