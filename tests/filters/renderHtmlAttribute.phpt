<?php

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('name validity', function () {
	Assert::type('string', Filters::renderHtmlAttribute('_name', ''));
	Assert::type('string', Filters::renderHtmlAttribute('42name', ''));
	Assert::type('string', Filters::renderHtmlAttribute('元素', '')); // Chinese for "element"
	Assert::type('string', Filters::renderHtmlAttribute('-my&HTML_element.name:2', ''));

	Assert::exception(fn() => Filters::renderHtmlAttribute('', ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderHtmlAttribute("name\n", ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderHtmlAttribute('name name', ''), Latte\RuntimeException::class);
	Assert::exception(fn() => Filters::renderHtmlAttribute('name"name', ''), Latte\RuntimeException::class);
});


test('Skipped attributes', function () {
	Assert::null(Filters::renderHtmlAttribute('title', false));
	Assert::null(Filters::renderHtmlAttribute('placeholder', null));
});


test('Regular text attributes', function () {
	Assert::same('title="Hello &amp; Welcome"', Filters::renderHtmlAttribute('title', 'Hello & Welcome'));
	Assert::same('title=\'"Hello" &amp; &apos;Welcome&apos;\'', Filters::renderHtmlAttribute('title', '"Hello" & \'Welcome\''));

	Assert::same('placeholder=""', Filters::renderHtmlAttribute('placeholder', ''));
});


test('boolean attributes', function () {
	Assert::same('disabled', Filters::renderHtmlAttribute('disabled', true));
	Assert::same('disabled', Filters::renderHtmlAttribute('disabled', 1));
	Assert::null(Filters::renderHtmlAttribute('disabled', false));
	Assert::null(Filters::renderHtmlAttribute('required', null));
	Assert::null(Filters::renderHtmlAttribute('readonly', 0));
});


test('style attribute', function () {
	Assert::same('style="color:red;"', Filters::renderHtmlAttribute('style', 'color:red;'));
	Assert::null(Filters::renderHtmlAttribute('style', []));

	Assert::same(
		'style="color:red;font-size:16px"',
		Filters::renderHtmlAttribute('style', ['color' => 'red', 'font-size' => '16px']),
	);
});


test('class attribute', function () {
	Assert::same('class="btn btn-primary"', Filters::renderHtmlAttribute('class', 'btn btn-primary'));
	Assert::null(Filters::renderHtmlAttribute('class', []));

	Assert::same(
		'class="btn red"',
		Filters::renderHtmlAttribute('class', ['btn', false, 'red', '']),
	);
});


test('data attributes', function () {
	Assert::same('data-foo="bar"', Filters::renderHtmlAttribute('data-foo', 'bar'));
	Assert::same('data-foo="0"', Filters::renderHtmlAttribute('data-foo', 0));

	Assert::same(
		'data-json=\'{"user":"Karel","age":30,"spec":"&<>\"\u0027\""}\'',
		Filters::renderHtmlAttribute('data-json', ['user' => 'Karel', 'age' => 30, 'spec' => '&<>"\'"']),
	);
});


test('special values (numbers, Infinity, NaN)', function () {
	Assert::same('width="0"', Filters::renderHtmlAttribute('width', 0));
	Assert::same('foo="NAN"', Filters::renderHtmlAttribute('foo', NAN));

	// invalid UTF-8 (is not processed)
	Assert::same("a=\"foo \u{D800} bar\"", Filters::renderHtmlAttribute('a', "foo \u{D800} bar")); // invalid codepoint high surrogates
	Assert::same("a='foo \xE3\x80\x22 bar'", Filters::renderHtmlAttribute('a', "foo \xE3\x80\x22 bar")); // stripped UTF
});
