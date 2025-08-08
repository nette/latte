<?php

/**
 * n:attr
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Str
{
	public function __toString()
	{
		return 'one&<br>';
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

test('text attributes', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span title="0"></span>
			<span title="123"></span>
			<span title=""></span>
			<span title="one&amp;two"></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span title="one&amp;"></span>
			<span title="one&amp;<br>"></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span n:attr="title => 0"></span>
			<span n:attr="title => 123"></span>
			<span n:attr="title => ''"></span>
			<span n:attr="title => 'one&two'"></span>
			<span n:attr="title => true"></span>
			<span n:attr="title => false"></span>
			<span n:attr="title => null"></span>
			<span n:attr="title => []"></span>
			<span n:attr="title => ['hello']"></span>
			<span n:attr="title => new Latte\Runtime\Html('one&amp;<br>')"></span>
			<span n:attr="title => new Str"></span>
			XX),
	);
});


test('boolean attributes', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span></span>
			<span disabled></span>
			<span></span>
			<span disabled></span>
			<span disabled></span>
			<span></span>
			<span></span>
			<span></span>
			<span disabled></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span n:attr="disabled => 0"></span>
			<span n:attr="disabled => 123"></span>
			<span n:attr="disabled => ''"></span>
			<span n:attr="disabled => 'one&two'"></span>
			<span n:attr="disabled => true"></span>
			<span n:attr="disabled => false"></span>
			<span n:attr="disabled => null"></span>
			<span n:attr="disabled => []"></span>
			<span n:attr="disabled => ['hello']"></span>
			XX),
	);
});


test('style attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span></span>
			<span></span>
			<span style=""></span>
			<span style="one&amp;two"></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span style="hello"></span>
			<span style="hello:one&amp;two"></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span n:attr="style => 0"></span>
			<span n:attr="style => 123"></span>
			<span n:attr="style => ''"></span>
			<span n:attr="style => 'one&two'"></span>
			<span n:attr="style => true"></span>
			<span n:attr="style => false"></span>
			<span n:attr="style => null"></span>
			<span n:attr="style => []"></span>
			<span n:attr="style => ['hello']"></span>
			<span n:attr="style => ['hello' => 'one&two']"></span>
			XX),
	);
});


test('space-separated attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span class="0"></span>
			<span class="123"></span>
			<span class=""></span>
			<span class="one&amp;two"></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span class="hello"></span>
			<span class="hello"></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span n:attr="class => 0"></span>
			<span n:attr="class => 123"></span>
			<span n:attr="class => ''"></span>
			<span n:attr="class => 'one&two'"></span>
			<span n:attr="class => true"></span>
			<span n:attr="class => false"></span>
			<span n:attr="class => null"></span>
			<span n:attr="class => []"></span>
			<span n:attr="class => ['hello']"></span>
			<span n:attr="class => ['hello' => true, 'world' => false]"></span>
			XX),
	);
});


test('on* attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span></span>
			<span></span>
			<span onclick=""></span>
			<span onclick="one&amp;two"></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span n:attr="onclick => 0"></span>
			<span n:attr="onclick => 123"></span>
			<span n:attr="onclick => ''"></span>
			<span n:attr="onclick => 'one&two'"></span>
			<span n:attr="onclick => true"></span>
			<span n:attr="onclick => false"></span>
			<span n:attr="onclick => null"></span>
			<span n:attr="onclick => []"></span>
			<span n:attr="onclick => ['hello']"></span>
			XX),
	);
});


test('data attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span data-foo="0"></span>
			<span data-foo="123"></span>
			<span data-foo=""></span>
			<span data-foo="one&amp;two"></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span n:attr="data-foo => 0"></span>
			<span n:attr="data-foo => 123"></span>
			<span n:attr="data-foo => ''"></span>
			<span n:attr="data-foo => 'one&two'"></span>
			<span n:attr="data-foo => true"></span>
			<span n:attr="data-foo => false"></span>
			<span n:attr="data-foo => null"></span>
			<span n:attr="data-foo => []"></span>
			<span n:attr="data-foo => ['hello']"></span>
			<span n:attr="data-foo => ['one&' => 'two&']"></span>
			XX),
	);
});


test('ARIA attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span aria-foo="0"></span>
			<span aria-foo="123"></span>
			<span aria-foo=""></span>
			<span aria-foo="one&amp;two"></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span n:attr="aria-foo => 0"></span>
			<span n:attr="aria-foo => 123"></span>
			<span n:attr="aria-foo => ''"></span>
			<span n:attr="aria-foo => 'one&two'"></span>
			<span n:attr="aria-foo => true"></span>
			<span n:attr="aria-foo => false"></span>
			<span n:attr="aria-foo => null"></span>
			<span n:attr="aria-foo => []"></span>
			<span n:attr="aria-foo => ['hello']"></span>
			<span n:attr="aria-foo => ['hello' => true]"></span>
			XX),
	);
});


test('href attribute', function () use ($latte) {
	Assert::match(
		'<a href="javascript:foo"></a>',
		$latte->renderToString('<a n:attr="href => \'javascript:foo\'"></a>'),
	);
});
