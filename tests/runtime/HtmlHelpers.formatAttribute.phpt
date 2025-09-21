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
		'title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
		HtmlHelpers::formatAttribute('title', '"Hello" & \'Welcome\''),
	);
	Assert::same('title=""', HtmlHelpers::formatAttribute('title', ''));

	// special values
	Assert::same('title', HtmlHelpers::formatAttribute('title', true));
	Assert::null(HtmlHelpers::formatAttribute('title', false));
	Assert::null(HtmlHelpers::formatAttribute('title', null));
	Assert::same('title="1"', HtmlHelpers::formatAttribute('title', 1));
	Assert::same('title="0"', HtmlHelpers::formatAttribute('title', 0));
	Assert::null(HtmlHelpers::formatAttribute('title', []));
	Assert::same('title="one&amp;amp;&lt;br&gt;"', HtmlHelpers::formatAttribute('title', new Latte\Runtime\Html('one&amp;<br>'))); // not supported
	Assert::same('title="one&amp;&lt;br&gt;"', HtmlHelpers::formatAttribute('title', new Str));

	// invalid
	Assert::exception(
		fn() => HtmlHelpers::formatAttribute('title', (object) []),
		Error::class,
	);
});


test('boolean attributes', function () {
	Assert::same('disabled', HtmlHelpers::formatAttribute('disabled', true));
	Assert::null(HtmlHelpers::formatAttribute('disabled', false));
	Assert::null(HtmlHelpers::formatAttribute('disabled', null));

	// special values
	Assert::same('disabled=""', HtmlHelpers::formatAttribute('disabled', ''));
	Assert::same('disabled="foo"', HtmlHelpers::formatAttribute('disabled', 'foo'));
	Assert::same('disabled="1"', HtmlHelpers::formatAttribute('disabled', 1));
	Assert::same('disabled="0"', HtmlHelpers::formatAttribute('disabled', 0));
	Assert::null(HtmlHelpers::formatAttribute('disabled', []));

	// invalid
	Assert::exception(
		fn() => HtmlHelpers::formatAttribute('disabled', (object) []),
		Error::class,
	);
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
	Assert::same('style', HtmlHelpers::formatAttribute('style', true));
	Assert::null(HtmlHelpers::formatAttribute('style', false));
	Assert::null(HtmlHelpers::formatAttribute('style', null));
	Assert::same('style="1"', HtmlHelpers::formatAttribute('style', 1));
	Assert::same('style="0"', HtmlHelpers::formatAttribute('style', 0));

	// invalid
	Assert::same(
		'style="color"',
		HtmlHelpers::formatAttribute('style', ['color' => true]),
	);
	Assert::exception(
		fn() => HtmlHelpers::formatAttribute('style', (object) []),
		Error::class,
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
	Assert::same('class', HtmlHelpers::formatAttribute('class', true));
	Assert::null(HtmlHelpers::formatAttribute('class', false));
	Assert::null(HtmlHelpers::formatAttribute('class', null));
	Assert::same('class="1"', HtmlHelpers::formatAttribute('class', 1));
	Assert::same('class="0"', HtmlHelpers::formatAttribute('class', 0));

	// invalid
	Assert::same(
		'class="btn:a red:b"',
		HtmlHelpers::formatAttribute('class', ['btn' => 'a', 'red' => 'b']),
	);
	Assert::exception(
		fn() => HtmlHelpers::formatAttribute('class', (object) []),
		Error::class,
	);
});


test('edge cases', function () {
	// invalid UTF-8
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \u{FFFD} bar\"",
		HtmlHelpers::formatAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a=\"foo \u{FFFD}&quot; bar\"",
		HtmlHelpers::formatAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});
