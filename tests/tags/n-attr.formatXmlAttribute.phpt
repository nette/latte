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


test('skipped attributes', function () {
	Assert::null(NAttrNode::formatXmlAttribute('foo', false));
	Assert::null(NAttrNode::formatXmlAttribute('foo', null));
});


test('regular text attributes', function () {
	Assert::same(
		'foo="Hello &amp; Welcome"',
		NAttrNode::formatXmlAttribute('foo', 'Hello & Welcome'),
	);
	Assert::same(
		'title=\'"Hello" &amp; &#39;Welcome&#39;\'',
		NAttrNode::formatXmlAttribute('title', '"Hello" & \'Welcome\''),
	);
	Assert::same('foo=""', NAttrNode::formatXmlAttribute('foo', ''));

	// special values
	Assert::same('foo="one&amp;amp;&lt;br>"', NAttrNode::formatXmlAttribute('foo', new Latte\Runtime\Html('one&amp;<br>'))); // not supported
	Assert::same('foo="one&amp;&lt;br>"', NAttrNode::formatXmlAttribute('foo', new StringObject));
});


test('boolean attributes', function () {
	Assert::same('foo="foo"', NAttrNode::formatXmlAttribute('foo', true));
	Assert::null(NAttrNode::formatXmlAttribute('foo', false));
});


test('special values', function () {
	Assert::same('foo="0"', NAttrNode::formatXmlAttribute('foo', 0));
	Assert::same('foo="1"', NAttrNode::formatXmlAttribute('foo', 1));
	Assert::null(NAttrNode::formatXmlAttribute('foo', []));

	// invalid UTF-8
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \xED\xA0\x80 bar\"",
		NAttrNode::formatXmlAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a='foo \xE3\x80\" bar'",
		NAttrNode::formatXmlAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});


test('invalid values', function () {
	Assert::error(
		fn() => Assert::null(NAttrNode::formatXmlAttribute('foo', (object) [])),
		Error::class,
	);
});
