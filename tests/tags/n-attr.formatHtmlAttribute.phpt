<?php

declare(strict_types=1);

use Latte\Essential\Nodes\NAttrNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class StringObject
{
	public function __toString()
	{
		return 'one&<br>';
	}
}


test('regular text attributes', function () {
	Assert::same(
		'title="Hello &amp; Welcome"',
		NAttrNode::formatHtmlAttribute('title', 'Hello & Welcome'),
	);
	Assert::same(
		'title="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
		NAttrNode::formatHtmlAttribute('title', '"Hello" & \'Welcome\''),
	);
	Assert::same('title=""', NAttrNode::formatHtmlAttribute('title', ''));

	// special values
	Assert::same('title', NAttrNode::formatHtmlAttribute('title', true));
	Assert::same('', NAttrNode::formatHtmlAttribute('title', false));
	Assert::same('', NAttrNode::formatHtmlAttribute('title', null));
	Assert::same('title="1"', NAttrNode::formatHtmlAttribute('title', 1));
	Assert::same('title="0"', NAttrNode::formatHtmlAttribute('title', 0));
	Assert::same('title="one&amp;"', NAttrNode::formatHtmlAttribute('title', new Latte\Runtime\Html('one&amp;<br>')));
	Assert::same('title="one&amp;&lt;br&gt;"', NAttrNode::formatHtmlAttribute('title', new StringObject));

	// invalid
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatHtmlAttribute('title', [])),
		E_USER_WARNING,
		"Invalid value for attribute 'title': array is not allowed.",
	);
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatHtmlAttribute('title', (object) [])),
		E_USER_WARNING,
		"Invalid value for attribute 'title': stdClass is not allowed.",
	);
});


test('boolean attributes', function () {
	Assert::same('checked', NAttrNode::formatHtmlAttribute('checked', true));
	Assert::same('', NAttrNode::formatHtmlAttribute('checked', false));
	Assert::same('', NAttrNode::formatHtmlAttribute('checked', null));

	// special values
	Assert::same('', NAttrNode::formatHtmlAttribute('checked', ''));
	Assert::same('checked', NAttrNode::formatHtmlAttribute('checked', 'foo'));
	Assert::same('checked', NAttrNode::formatHtmlAttribute('checked', 1));
	Assert::same('', NAttrNode::formatHtmlAttribute('checked', 0));
	Assert::same('', NAttrNode::formatHtmlAttribute('checked', []));
	Assert::same('checked', NAttrNode::formatHtmlAttribute('checked', [1]));
	Assert::same('checked', NAttrNode::formatHtmlAttribute('checked', (object) []));
});


test('style attribute', function () {
	Assert::same('style=""', NAttrNode::formatHtmlAttribute('style', ''));
	Assert::same('style="color:red;"', NAttrNode::formatHtmlAttribute('style', 'color:red;'));
	Assert::same('', NAttrNode::formatHtmlAttribute('style', []));

	Assert::same(
		'style="color: red; font-size: 16px"',
		NAttrNode::formatHtmlAttribute('style', ['color' => 'red', 'font-size' => '16px']),
	);
	Assert::same(
		'style="color: red; font-size: 16px"',
		NAttrNode::formatHtmlAttribute('style', ['color: red', 'font-size: 16px']),
	);

	// special values
	Assert::same('', NAttrNode::formatHtmlAttribute('style', false));
	Assert::same('', NAttrNode::formatHtmlAttribute('style', null));

	// invalid
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatHtmlAttribute('style', true)),
		E_USER_WARNING,
		"Invalid value for attribute 'style': bool is not allowed.",
	);
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatHtmlAttribute('style', (object) [])),
		E_USER_WARNING,
		"Invalid value for attribute 'style': stdClass is not allowed.",
	);
});


test('space-separated attribute', function () {
	Assert::same('class=""', NAttrNode::formatHtmlAttribute('class', ''));
	Assert::same('class="btn btn-primary"', NAttrNode::formatHtmlAttribute('class', 'btn btn-primary'));
	Assert::same('', NAttrNode::formatHtmlAttribute('class', []));

	Assert::same(
		'class="btn red"',
		NAttrNode::formatHtmlAttribute('class', ['btn', false, 'red', '']),
	);
	Assert::same(
		'class="btn"',
		NAttrNode::formatHtmlAttribute('class', ['btn' => true, 'red' => false]),
	);
	Assert::same(
		'class="a b"',
		NAttrNode::formatHtmlAttribute('class', ['btn' => 'a', 'red' => 'b']),
	);

	// special values
	Assert::same('', NAttrNode::formatHtmlAttribute('class', false));
	Assert::same('', NAttrNode::formatHtmlAttribute('class', null));
	Assert::same('class="1"', NAttrNode::formatHtmlAttribute('class', 1));
	Assert::same('class="0"', NAttrNode::formatHtmlAttribute('class', 0));

	// invalid
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatHtmlAttribute('class', true)),
		E_USER_WARNING,
		"Invalid value for attribute 'class': bool is not allowed.",
	);
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatHtmlAttribute('class', (object) [])),
		E_USER_WARNING,
		"Invalid value for attribute 'class': stdClass is not allowed.",
	);
});


test('data attributes', function () {
	Assert::same('data-foo=""', NAttrNode::formatHtmlAttribute('data-foo', ''));
	Assert::same('data-foo="bar"', NAttrNode::formatHtmlAttribute('data-foo', 'bar'));
	Assert::same('data-foo="1"', NAttrNode::formatHtmlAttribute('data-foo', 1));
	Assert::same('data-foo="0"', NAttrNode::formatHtmlAttribute('data-foo', 0));

	Assert::same('data-foo="true"', NAttrNode::formatHtmlAttribute('data-foo', true));
	Assert::same('data-foo="false"', NAttrNode::formatHtmlAttribute('data-foo', false));
	Assert::same('', NAttrNode::formatHtmlAttribute('data-foo', null));

	Assert::same('data-foo="[]"', NAttrNode::formatHtmlAttribute('data-foo', []));
	Assert::same('data-foo="{}"', NAttrNode::formatHtmlAttribute('data-foo', (object) []));
	Assert::same(
		'data-foo=\'{"user":"Karel","age":30,"spec":"&amp;<>\"&apos;\""}\'',
		NAttrNode::formatHtmlAttribute('data-foo', ['user' => 'Karel', 'age' => 30, 'spec' => '&<>"\'"']),
	);
});


test('ARIA attributes', function () {
	Assert::same('aria-foo=""', NAttrNode::formatHtmlAttribute('aria-foo', ''));
	Assert::same('aria-foo="bar"', NAttrNode::formatHtmlAttribute('aria-foo', 'bar'));
	Assert::same('aria-foo="1"', NAttrNode::formatHtmlAttribute('aria-foo', 1));
	Assert::same('aria-foo="0"', NAttrNode::formatHtmlAttribute('aria-foo', 0));

	Assert::same('aria-foo="true"', NAttrNode::formatHtmlAttribute('aria-foo', true));
	Assert::same('aria-foo="false"', NAttrNode::formatHtmlAttribute('aria-foo', false));
	Assert::same('', NAttrNode::formatHtmlAttribute('aria-foo', null));

	Assert::same('', NAttrNode::formatHtmlAttribute('aria-foo', []));
	Assert::same('aria-foo="a b"', NAttrNode::formatHtmlAttribute('aria-foo', ['a', 'b']));
	Assert::same('aria-foo="Karel"', NAttrNode::formatHtmlAttribute('aria-foo', ['user' => 'Karel']));

	// invalid
	Assert::error(
		fn() => NAttrNode::formatHtmlAttribute('aria-foo', (object) []),
		E_USER_WARNING,
	);
});


test('edge cases', function () {
	// invalid UTF-8 (is not processed)
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \u{FFFD} bar\"",
		NAttrNode::formatHtmlAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a=\"foo \u{FFFD}&quot; bar\"",
		NAttrNode::formatHtmlAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});
