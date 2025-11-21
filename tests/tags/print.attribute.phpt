<?php

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


$latte = createLatte();


test('text attributes', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span title="0"></span>
			<span title="123"></span>
			<span title=""></span>
			<span title="one&amp;two&apos;&quot;"></span>
			<span title="ONE&amp;TWO&apos;&quot;"></span>
			<span title="1"></span>
			<span title=""></span>
			<span></span>
			<span title="one&amp;"></span>
			<span title="one&amp;&lt;br&gt; &quot;&apos;"></span>
			<span title="Array"></span>

			<span title="123 -- one&amp;two&apos;&quot; -- 1 --  --  -- one&amp; -- one&amp;&lt;br&gt; &quot;&apos;"></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span title={=0}></span>
			<span title={=123}></span>
			<span title={=''}></span>
			<span title={='one&two\'"'}></span>
			<span title={='one&two\'"'|upper}></span>
			<span title={=true}></span>
			<span title={=false}></span>
			<span title={=null}></span>
			<span title={=new Latte\Runtime\Html('one&amp;<br>')}></span>
			<span title={=new StringObject}></span>
			<span title={=[]}></span>

			<span title="{=123} -- {='one&two\'"'} -- {=true} -- {=false} -- {=null} -- {=new Latte\Runtime\Html('one&amp;<br>')} -- {=new StringObject}"></span>
			XX),
	);
});


test('|noescape', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span title="one&amp;<br> &quot;&apos;"></span>
			<span title="1"></span>
			<span title=""></span>
			<span title=""></span>
			<span title="one&amp;<br> &quot;&apos;"></span>
			<span title="one&<br> &quot;&apos;"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span title={='one&amp;<br> "\''|noescape}></span>
			<span title={=true|noescape}></span>
			<span title={=false|noescape}></span>
			<span title={=null|noescape}></span>
			<span title={=new Latte\Runtime\Html('one&amp;<br> "\'')|noescape}></span>
			<span title={=new StringObject|noescape}></span>
			XX),
	);
});


test('boolean attributes', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span></span>
			<span checked></span>
			<span></span>
			<span checked></span>
			<span checked></span>
			<span></span>
			<span></span>
			<span></span>
			<span checked></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span checked={=0}></span>
			<span checked={=123}></span>
			<span checked={=''}></span>
			<span checked={='one&two'}></span>
			<span checked={=true}></span>
			<span checked={=false}></span>
			<span checked={=null}></span>
			<span checked={=[]}></span>
			<span checked={=[1]}></span>
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
			<span style="1"></span>
			<span style=""></span>
			<span></span>
			<span></span>
			<span style="a: b"></span>
			<span style="a; b"></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span style={=0}></span>
			<span style={=123}></span>
			<span style={=''}></span>
			<span style={='one&two'}></span>
			<span style={=true}></span>
			<span style={=false}></span>
			<span style={=null}></span>
			<span style={=[]}></span>
			<span style={=['a' => 'b']}></span>
			<span style={=['a', 'b']}></span>
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
			<span class="1"></span>
			<span class=""></span>
			<span></span>
			<span></span>
			<span class="b"></span>
			<span class="a b"></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span class={=0}></span>
			<span class={=123}></span>
			<span class={=''}></span>
			<span class={='one&two'}></span>
			<span class={=true}></span>
			<span class={=false}></span>
			<span class={=null}></span>
			<span class={=[]}></span>
			<span class={=['a' => 'b']}></span>
			<span class={=['a', 'b']}></span>
			XX),
	);
});


test('on* attribute', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<span onclick="0"></span>
			<span onclick="123"></span>
			<span onclick=""></span>
			<span onclick="one&amp;two"></span>
			<span onclick="1"></span>
			<span onclick=""></span>
			<span></span>

			<span onclick="123 -- &quot;one&amp;two&apos;\&quot;&quot; -- true -- false -- null -- &quot;one&amp;amp;&lt;br&gt;&quot; -- &#123;}"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span onclick={=0}></span>
			<span onclick={=123}></span>
			<span onclick={=''}></span>
			<span onclick={='one&two'}></span>
			<span onclick={=true}></span>
			<span onclick={=false}></span>
			<span onclick={=null}></span>

			<span onclick="{=123} -- {='one&two\'"'} -- {=true} -- {=false} -- {=null} -- {=new Latte\Runtime\Html('one&amp;<br>')} -- {=new StringObject}"></span>
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
			<span data-foo="true"></span>
			<span data-foo="false"></span>
			<span></span>
			<span data-foo="[]"></span>
			<span data-foo='{"a":"b"}'></span>
			<span data-foo='["a","b"]'></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span data-foo={=0}></span>
			<span data-foo={=123}></span>
			<span data-foo={=''}></span>
			<span data-foo={='one&two'}></span>
			<span data-foo={=true}></span>
			<span data-foo={=false}></span>
			<span data-foo={=null}></span>
			<span data-foo={=[]}></span>
			<span data-foo={=['a' => 'b']}></span>
			<span data-foo={=['a', 'b']}></span>
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
			<span aria-foo="true"></span>
			<span aria-foo="false"></span>
			<span></span>
			<span></span>
			<span aria-foo="b"></span>
			<span aria-foo="a b"></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span aria-foo={=0}></span>
			<span aria-foo={=123}></span>
			<span aria-foo={=''}></span>
			<span aria-foo={='one&two'}></span>
			<span aria-foo={=true}></span>
			<span aria-foo={=false}></span>
			<span aria-foo={=null}></span>
			<span aria-foo={=[]}></span>
			<span aria-foo={=['a' => 'b']}></span>
			<span aria-foo={=['a', 'b']}></span>
			XX),
	);
});


test('href attribute', function () {
	$latte = createLatte();
	Assert::match(
		<<<'XX'
			<a href=""></a>
			<a href="javascript:foo"></a>
			<a href="javascript:foo"></a>
			<a href=""></a>
			XX,
		$latte->renderToString(
			<<<'XX'
				<a href={='javascript:foo'}></a>
				<a href={='javascript:foo'|noescape}></a>
				<a href={='javascript:foo'|nocheck}></a>
				<a href={='javascript:foo'|trim}></a>
				XX,
		),
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
			<bar foo="1"></bar>
			<bar foo=""></bar>
			<bar></bar>
			<bar foo="Array"></bar>
			XX,
		@$latte->renderToString(<<<'XX'
			{contentType xml}
			<bar foo={=0}></bar>
			<bar foo={=123}></bar>
			<bar foo={=''}></bar>
			<bar foo={='one&two'}></bar>
			<bar foo={=true}></bar>
			<bar foo={=false}></bar>
			<bar foo={=null}></bar>
			<bar foo={=[]}></bar>
			XX),
	);
});
