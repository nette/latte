<?php declare(strict_types=1);

/**
 * n:attr
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class StringObject
{
	public function __toString()
	{
		return 'one&<br> "\'';
	}
}


test('text attributes', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span title="0"></span>
			<span title="123"></span>
			<span title=""></span>
			<span title="one&amp;two &apos;"></span>
			<span title></span>
			<span></span>
			<span></span>
			<span></span>
			<span title="hello one&amp;two"></span>
			<span title="hello:one&amp;two"></span>
			<span title="hello"></span>
			<span title="one&amp;amp;&lt;br&gt; &apos;"></span>
			<span title="one&amp;&lt;br&gt; &quot;&apos;"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span n:attr="title => 0"></span>
			<span n:attr="title => 123"></span>
			<span n:attr="title => ''"></span>
			<span n:attr="title => 'one&two \''"></span>
			<span n:attr="title => true"></span>
			<span n:attr="title => false"></span>
			<span n:attr="title => null"></span>
			<span n:attr="title => []"></span>
			<span n:attr="title => ['hello', 'one&two']"></span>
			<span n:attr="title => ['hello' => 'one&two']"></span>
			<span n:attr="title => ['hello' => true, 'world' => false]"></span>
			<span n:attr="title => new Latte\Runtime\Html('one&amp;<br> \'')"></span>
			<span n:attr="title => new StringObject"></span>
			XX),
	);
});


test('style attribute', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span style="0"></span>
			<span style="123"></span>
			<span style=""></span>
			<span style="one&amp;two"></span>
			<span style></span>
			<span></span>
			<span></span>
			<span></span>
			<span style="hello;one&amp;two"></span>
			<span style="hello:one&amp;two"></span>
			<span style="hello"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span n:attr="style => 0"></span>
			<span n:attr="style => 123"></span>
			<span n:attr="style => ''"></span>
			<span n:attr="style => 'one&two'"></span>
			<span n:attr="style => true"></span>
			<span n:attr="style => false"></span>
			<span n:attr="style => null"></span>
			<span n:attr="style => []"></span>
			<span n:attr="style => ['hello', 'one&two']"></span>
			<span n:attr="style => ['hello' => 'one&two']"></span>
			<span n:attr="style => ['hello' => true, 'world' => false]"></span>
			XX),
	);
});


test('href attribute', function () {
	$latte = createLatte();
	// not checked
	Assert::match(
		<<<'XX'
			<a href="javascript:foo"></a>
			XX,
		$latte->renderToString(<<<'XX'
			<a n:attr="href => 'javascript:foo'"></a>
			XX),
	);
});


test('XML', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<bar foo="0"></bar>
			<bar foo="123"></bar>
			<bar foo=""></bar>
			<bar foo="one&amp;two"></bar>
			<bar foo="foo"></bar>
			<bar></bar>
			<bar></bar>
			<bar></bar>
			<bar foo="hello one&amp;two"></bar>
			<bar foo="one&amp;two"></bar>
			<bar foo="1"></bar>
			XX,
		$latte->renderToString(<<<'XX'
			{contentType xml}
			<bar n:attr="foo => 0"></bar>
			<bar n:attr="foo => 123"></bar>
			<bar n:attr="foo => ''"></bar>
			<bar n:attr="foo => 'one&two'"></bar>
			<bar n:attr="foo => true"></bar>
			<bar n:attr="foo => false"></bar>
			<bar n:attr="foo => null"></bar>
			<bar n:attr="foo => []"></bar>
			<bar n:attr="foo => ['hello', 'one&two']"></bar>
			<bar n:attr="foo => ['hello' => 'one&two']"></bar>
			<bar n:attr="foo => ['hello' => true, 'world' => false]"></bar>
			XX),
	);
});
