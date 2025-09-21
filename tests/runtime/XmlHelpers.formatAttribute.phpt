<?php

declare(strict_types=1);

use Latte\Runtime\XmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Str
{
	public function __toString()
	{
		return 'one&<br>';
	}
}


test('skipped attributes', function () {
	Assert::null(XmlHelpers::formatAttribute('foo', false));
	Assert::null(XmlHelpers::formatAttribute('foo', null));
});


test('regular text attributes', function () {
	Assert::same(
		'foo="Hello &amp; Welcome"',
		XmlHelpers::formatAttribute('foo', 'Hello & Welcome'),
	);
	Assert::same(
		'foo="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
		XmlHelpers::formatAttribute('foo', '"Hello" & \'Welcome\''),
	);
	Assert::same('foo=""', XmlHelpers::formatAttribute('foo', ''));

	// special values
	Assert::same('foo="one&amp;amp;&lt;br&gt;"', XmlHelpers::formatAttribute('foo', new Latte\Runtime\Html('one&amp;<br>'))); // not supported
	Assert::same('foo="one&amp;&lt;br&gt;"', XmlHelpers::formatAttribute('foo', new Str));
});


test('boolean attributes', function () {
	Assert::same('foo="foo"', XmlHelpers::formatAttribute('foo', true));
	Assert::null(XmlHelpers::formatAttribute('foo', false));
});


test('special values', function () {
	Assert::same('foo="0"', XmlHelpers::formatAttribute('foo', 0));
	Assert::same('foo="1"', XmlHelpers::formatAttribute('foo', 1));
	Assert::null(XmlHelpers::formatAttribute('foo', []));

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
		fn() => Assert::null(XmlHelpers::formatAttribute('foo', (object) [])),
		Error::class,
	);
});
