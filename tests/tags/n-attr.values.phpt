<?php

/**
 * n:attr
 */

declare(strict_types=1);

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


test('boolean attributes', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span checked="0"></span>
			<span checked="123"></span>
			<span checked=""></span>
			<span checked="one&amp;two"></span>
			<span checked></span>
			<span></span>
			<span></span>
			<span></span>
			<span checked="hello"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span n:attr="checked => 0"></span>
			<span n:attr="checked => 123"></span>
			<span n:attr="checked => ''"></span>
			<span n:attr="checked => 'one&two'"></span>
			<span n:attr="checked => true"></span>
			<span n:attr="checked => false"></span>
			<span n:attr="checked => null"></span>
			<span n:attr="checked => []"></span>
			<span n:attr="checked => ['hello']"></span>
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


test('space-separated attribute', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span class="0"></span>
			<span class="123"></span>
			<span class=""></span>
			<span class="one&amp;two"></span>
			<span class></span>
			<span></span>
			<span></span>
			<span></span>
			<span class="hello one&amp;two"></span>
			<span class="hello:one&amp;two"></span>
			<span class="hello"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span n:attr="class => 0"></span>
			<span n:attr="class => 123"></span>
			<span n:attr="class => ''"></span>
			<span n:attr="class => 'one&two'"></span>
			<span n:attr="class => true"></span>
			<span n:attr="class => false"></span>
			<span n:attr="class => null"></span>
			<span n:attr="class => []"></span>
			<span n:attr="class => ['hello', 'one&two']"></span>
			<span n:attr="class => ['hello' => 'one&two']"></span>
			<span n:attr="class => ['hello' => true, 'world' => false]"></span>
			XX),
	);
});


test('data attribute', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span data-foo="0"></span>
			<span data-foo="123"></span>
			<span data-foo=""></span>
			<span data-foo="one&amp;two"></span>
			<span data-foo></span>
			<span></span>
			<span></span>
			<span></span>
			<span data-foo="hello one&amp;two"></span>
			<span data-foo="hello&amp;:one&amp;two"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span n:attr="data-foo => 0"></span>
			<span n:attr="data-foo => 123"></span>
			<span n:attr="data-foo => ''"></span>
			<span n:attr="data-foo => 'one&two'"></span>
			<span n:attr="data-foo => true"></span>
			<span n:attr="data-foo => false"></span>
			<span n:attr="data-foo => null"></span>
			<span n:attr="data-foo => []"></span>
			<span n:attr="data-foo => ['hello', 'one&two']"></span>
			<span n:attr="data-foo => ['hello&' => 'one&two']"></span>
			XX),
	);
});


test('ARIA attribute', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span aria-foo="0"></span>
			<span aria-foo="123"></span>
			<span aria-foo=""></span>
			<span aria-foo="one&amp;two"></span>
			<span aria-foo></span>
			<span></span>
			<span></span>
			<span></span>
			<span aria-foo="hello one&amp;two"></span>
			<span aria-foo="hello"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span n:attr="aria-foo => 0"></span>
			<span n:attr="aria-foo => 123"></span>
			<span n:attr="aria-foo => ''"></span>
			<span n:attr="aria-foo => 'one&two'"></span>
			<span n:attr="aria-foo => true"></span>
			<span n:attr="aria-foo => false"></span>
			<span n:attr="aria-foo => null"></span>
			<span n:attr="aria-foo => []"></span>
			<span n:attr="aria-foo => ['hello', 'one&two']"></span>
			<span n:attr="aria-foo => ['hello' => true]"></span>
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
