<?php declare(strict_types=1);

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
	Assert::same('', NAttrNode::formatXmlAttribute('foo', false));
	Assert::same('', NAttrNode::formatXmlAttribute('foo', null));
});


test('regular text attributes', function () {
	Assert::same(
		'foo="Hello &amp; Welcome"',
		NAttrNode::formatXmlAttribute('foo', 'Hello & Welcome'),
	);
	Assert::same(
		'foo="&quot;Hello&quot; &amp; &apos;Welcome&apos;"',
		NAttrNode::formatXmlAttribute('foo', '"Hello" & \'Welcome\''),
	);
	Assert::same('foo=""', NAttrNode::formatXmlAttribute('foo', ''));

	// special values
	Assert::same('foo="one&amp;"', NAttrNode::formatXmlAttribute('foo', new Latte\Runtime\Html('one&amp;')));
	Assert::same('foo="one&amp;&lt;br&gt;"', NAttrNode::formatXmlAttribute('foo', new StringObject));
});


test('special values', function () {
	Assert::same('foo="0"', NAttrNode::formatXmlAttribute('foo', 0));
	Assert::same('foo="1"', NAttrNode::formatXmlAttribute('foo', 1));

	// invalid UTF-8
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \u{FFFD} bar\"",
		NAttrNode::formatXmlAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a=\"foo \u{FFFD}&quot; bar\"",
		NAttrNode::formatXmlAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});


test('invalid values', function () {
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatXmlAttribute('foo', true)),
		E_USER_WARNING,
		"Invalid value for attribute 'foo': bool is not allowed.",
	);
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatXmlAttribute('foo', [])),
		E_USER_WARNING,
		"Invalid value for attribute 'foo': array is not allowed.",
	);
	Assert::error(
		fn() => Assert::same('', NAttrNode::formatXmlAttribute('foo', (object) [])),
		E_USER_WARNING,
		"Invalid value for attribute 'foo': stdClass is not allowed.",
	);
});
