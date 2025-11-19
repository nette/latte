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
		'title=\'"Hello" &amp; &apos;Welcome&apos;\'',
		NAttrNode::formatHtmlAttribute('title', '"Hello" & \'Welcome\''),
	);
	Assert::same('title=""', NAttrNode::formatHtmlAttribute('title', ''));

	// special values
	Assert::same('title', NAttrNode::formatHtmlAttribute('title', true));
	Assert::null(NAttrNode::formatHtmlAttribute('title', false));
	Assert::null(NAttrNode::formatHtmlAttribute('title', null));
	Assert::same('title="1"', NAttrNode::formatHtmlAttribute('title', 1));
	Assert::same('title="0"', NAttrNode::formatHtmlAttribute('title', 0));
	Assert::null(NAttrNode::formatHtmlAttribute('title', []));
	Assert::same('title="one&amp;amp;<br>"', NAttrNode::formatHtmlAttribute('title', new Latte\Runtime\Html('one&amp;<br>'))); // not supported
	Assert::same('title="one&amp;<br>"', NAttrNode::formatHtmlAttribute('title', new StringObject));

	// invalid
	Assert::exception(
		fn() => NAttrNode::formatHtmlAttribute('title', (object) []),
		Error::class,
	);
});


test('boolean attributes', function () {
	Assert::same('checked', NAttrNode::formatHtmlAttribute('checked', true));
	Assert::null(NAttrNode::formatHtmlAttribute('checked', false));
	Assert::null(NAttrNode::formatHtmlAttribute('checked', null));

	// special values
	Assert::same('checked=""', NAttrNode::formatHtmlAttribute('checked', ''));
	Assert::same('checked="foo"', NAttrNode::formatHtmlAttribute('checked', 'foo'));
	Assert::same('checked="1"', NAttrNode::formatHtmlAttribute('checked', 1));
	Assert::same('checked="0"', NAttrNode::formatHtmlAttribute('checked', 0));
	Assert::null(NAttrNode::formatHtmlAttribute('checked', []));

	// invalid
	Assert::exception(
		fn() => NAttrNode::formatHtmlAttribute('checked', (object) []),
		Error::class,
	);
});


test('style attribute', function () {
	Assert::same('style=""', NAttrNode::formatHtmlAttribute('style', ''));
	Assert::same('style="color:red;"', NAttrNode::formatHtmlAttribute('style', 'color:red;'));
	Assert::null(NAttrNode::formatHtmlAttribute('style', []));

	Assert::same(
		'style="color:red;font-size:16px"',
		NAttrNode::formatHtmlAttribute('style', ['color' => 'red', 'font-size' => '16px']),
	);
	Assert::same(
		'style="color: red;font-size: 16px"',
		NAttrNode::formatHtmlAttribute('style', ['color: red', 'font-size: 16px']),
	);

	// special values
	Assert::same('style', NAttrNode::formatHtmlAttribute('style', true));
	Assert::null(NAttrNode::formatHtmlAttribute('style', false));
	Assert::null(NAttrNode::formatHtmlAttribute('style', null));
	Assert::same('style="1"', NAttrNode::formatHtmlAttribute('style', 1));
	Assert::same('style="0"', NAttrNode::formatHtmlAttribute('style', 0));

	// invalid
	Assert::same(
		'style="color"',
		NAttrNode::formatHtmlAttribute('style', ['color' => true]),
	);
	Assert::exception(
		fn() => NAttrNode::formatHtmlAttribute('style', (object) []),
		Error::class,
	);
});


test('space-separated attribute', function () {
	Assert::same('class=""', NAttrNode::formatHtmlAttribute('class', ''));
	Assert::same('class="btn btn-primary"', NAttrNode::formatHtmlAttribute('class', 'btn btn-primary'));
	Assert::null(NAttrNode::formatHtmlAttribute('class', []));

	Assert::same(
		'class="btn red"',
		NAttrNode::formatHtmlAttribute('class', ['btn', false, 'red', '']),
	);
	Assert::same(
		'class="btn"',
		NAttrNode::formatHtmlAttribute('class', ['btn' => true, 'red' => false]),
	);

	// special values
	Assert::same('class', NAttrNode::formatHtmlAttribute('class', true));
	Assert::null(NAttrNode::formatHtmlAttribute('class', false));
	Assert::null(NAttrNode::formatHtmlAttribute('class', null));
	Assert::same('class="1"', NAttrNode::formatHtmlAttribute('class', 1));
	Assert::same('class="0"', NAttrNode::formatHtmlAttribute('class', 0));

	// invalid
	Assert::same(
		'class="btn:a red:b"',
		NAttrNode::formatHtmlAttribute('class', ['btn' => 'a', 'red' => 'b']),
	);
	Assert::exception(
		fn() => NAttrNode::formatHtmlAttribute('class', (object) []),
		Error::class,
	);
});


test('edge cases', function () {
	// invalid UTF-8 (is not processed)
	Assert::same( // invalid codepoint high surrogates
		"a=\"foo \u{D800} bar\"",
		NAttrNode::formatHtmlAttribute('a', "foo \u{D800} bar"),
	);
	Assert::same( // stripped UTF
		"a='foo \xE3\x80\x22 bar'",
		NAttrNode::formatHtmlAttribute('a', "foo \xE3\x80\x22 bar"),
	);
});
