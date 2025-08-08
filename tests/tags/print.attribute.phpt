<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Str
{
	public function __toString()
	{
		return 'one&<br> "\'';
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
			<span title="one&amp;two&apos;&quot;"></span>
			<span title="1"></span>
			<span title=""></span>
			<span title=""></span>
			<span title="one&amp;"></span>
			<span title="one&amp;&lt;br&gt; &quot;&apos;"></span>

			<span title="123 -- one&amp;two&apos;&quot; -- 1 --  --  -- one&amp; -- one&amp;&lt;br&gt; &quot;&apos;"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span title="{=0}"></span>
			<span title="{=123}"></span>
			<span title="{=''}"></span>
			<span title="{='one&two\'"'}"></span>
			<span title="{=true}"></span>
			<span title="{=false}"></span>
			<span title="{=null}"></span>
			<span title="{=new Latte\Runtime\Html('one&amp;<br>')}"></span>
			<span title="{=new Str}"></span>

			<span title="{=123} -- {='one&two\'"'} -- {=true} -- {=false} -- {=null} -- {=new Latte\Runtime\Html('one&amp;<br>')} -- {=new Str}"></span>
			XX),
	);
});


test('style attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span style="0"></span>
			<span style="123"></span>
			<span style=""></span>
			<span style="one\&amp;two"></span>
			<span style="1"></span>
			<span style=""></span>
			<span style=""></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span style="{=0}"></span>
			<span style="{=123}"></span>
			<span style="{=''}"></span>
			<span style="{='one&two'}"></span>
			<span style="{=true}"></span>
			<span style="{=false}"></span>
			<span style="{=null}"></span>
			XX),
	);
});


test('on* attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span onclick="0"></span>
			<span onclick="123"></span>
			<span onclick="&quot;&quot;"></span>
			<span onclick="&quot;one&amp;two&quot;"></span>
			<span onclick="true"></span>
			<span onclick="false"></span>
			<span onclick="null"></span>

			<span onclick="123 -- &quot;one&amp;two&apos;\&quot;&quot; -- true -- false -- null -- &quot;one&amp;amp;&lt;br&gt;&quot; -- &#123;}"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span onclick="{=0}"></span>
			<span onclick="{=123}"></span>
			<span onclick="{=''}"></span>
			<span onclick="{='one&two'}"></span>
			<span onclick="{=true}"></span>
			<span onclick="{=false}"></span>
			<span onclick="{=null}"></span>

			<span onclick="{=123} -- {='one&two\'"'} -- {=true} -- {=false} -- {=null} -- {=new Latte\Runtime\Html('one&amp;<br>')} -- {=new Str}"></span>
			XX),
	);
});


test('href attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<a href=""></a>
			<a href=""></a>
			<a href="javascript:foo"></a>
			<a href=""></a>
			XX,
		$latte->renderToString(
			<<<'XX'
				<a href="{='javascript:foo'}"></a>
				<a href="{='javascript:foo'|noescape}"></a>
				<a href="{='javascript:foo'|nocheck}"></a>
				<a href="{='javascript:foo'|trim}"></a>
				XX,
		),
	);
});


test('|noescape', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span title="one&amp;<br> &quot;&apos;"></span>
			<span title="1"></span>
			<span title=""></span>
			<span title=""></span>
			<span title="one&amp;<br> &quot;&apos;"></span>
			<span title="one&<br> &quot;&apos;"></span>

			<span style="one&two"></span>
			<span onclick="one&two"></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span title="{='one&amp;<br> "\''|noescape}"></span>
			<span title="{=true|noescape}"></span>
			<span title="{=false|noescape}"></span>
			<span title="{=null|noescape}"></span>
			<span title="{=new Latte\Runtime\Html('one&amp;<br> "\'')|noescape}"></span>
			<span title="{=new Str|noescape}"></span>

			<span style="{='one&two'|noescape}"></span>
			<span onclick="{='one&two'|noescape}"></span>
			XX),
	);
});


test('XML', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<bar foo="0"></bar>
			<bar foo="123"></bar>
			<bar foo=""></bar>
			<bar foo="one&amp;two"></bar>
			<bar foo="1"></bar>
			<bar foo=""></bar>
			<bar foo=""></bar>
			XX,
		$latte->renderToString(<<<'XX'
			{contentType xml}
			<bar foo="{=0}"></bar>
			<bar foo="{=123}"></bar>
			<bar foo="{=''}"></bar>
			<bar foo="{='one&two'}"></bar>
			<bar foo="{=true}"></bar>
			<bar foo="{=false}"></bar>
			<bar foo="{=null}"></bar>
			XX),
	);
});
