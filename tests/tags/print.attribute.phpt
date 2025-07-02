<?php

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
			<span ></span>
			<span ></span>
			<span ></span>
			<span title="one&amp;"></span>
			<span title="one&amp;<br>"></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span title="{=0}"></span>
			<span title="{=123}"></span>
			<span title="{=''}"></span>
			<span title="{='one&two'}"></span>
			<span title="{=true}"></span>
			<span title="{=false}"></span>
			<span title="{=null}"></span>
			<span title="{=new Latte\Runtime\Html('one&amp;<br>')}"></span>
			<span title="{=new Str}"></span>
			XX),
	);
});


test('boolean attributes', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span ></span>
			<span disabled></span>
			<span ></span>
			<span disabled></span>
			<span disabled></span>
			<span ></span>
			<span ></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span disabled="{=0}"></span>
			<span disabled="{=123}"></span>
			<span disabled="{=''}"></span>
			<span disabled="{='one&two'}"></span>
			<span disabled="{=true}"></span>
			<span disabled="{=false}"></span>
			<span disabled="{=null}"></span>
			XX),
	);
});


test('style attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span ></span>
			<span ></span>
			<span style=""></span>
			<span style="one&amp;two"></span>
			<span ></span>
			<span ></span>
			<span ></span>
			XX,
		@$latte->renderToString(<<<'XX'
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


test('space-separated attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span class="0"></span>
			<span class="123"></span>
			<span class=""></span>
			<span class="one&amp;two"></span>
			<span ></span>
			<span ></span>
			<span ></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span class="{=0}"></span>
			<span class="{=123}"></span>
			<span class="{=''}"></span>
			<span class="{='one&two'}"></span>
			<span class="{=true}"></span>
			<span class="{=false}"></span>
			<span class="{=null}"></span>
			XX),
	);
});


test('on* attribute', function () use ($latte) {
	Assert::match(
		<<<'XX'
			<span ></span>
			<span ></span>
			<span onclick=""></span>
			<span onclick="one&amp;two"></span>
			<span ></span>
			<span ></span>
			<span ></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span onclick="{=0}"></span>
			<span onclick="{=123}"></span>
			<span onclick="{=''}"></span>
			<span onclick="{='one&two'}"></span>
			<span onclick="{=true}"></span>
			<span onclick="{=false}"></span>
			<span onclick="{=null}"></span>
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
			<span data-foo="1"></span>
			<span data-foo=""></span>
			<span data-foo></span>
			XX,
		@$latte->renderToString(<<<'XX'
			<span data-foo="{=0}"></span>
			<span data-foo="{=123}"></span>
			<span data-foo="{=''}"></span>
			<span data-foo="{='one&two'}"></span>
			<span data-foo="{=true}"></span>
			<span data-foo="{=false}"></span>
			<span data-foo="{=null}"></span>
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
			<span aria-foo="true"></span>
			<span aria-foo="false"></span>
			<span ></span>
			XX,
		$latte->renderToString(<<<'XX'
			<span aria-foo="{=0}"></span>
			<span aria-foo="{=123}"></span>
			<span aria-foo="{=''}"></span>
			<span aria-foo="{='one&two'}"></span>
			<span aria-foo="{=true}"></span>
			<span aria-foo="{=false}"></span>
			<span aria-foo="{=null}"></span>
			XX),
	);
});


test('href attribute', function () use ($latte) {
	Assert::match(
		'<a href=""></a>',
		$latte->renderToString('<a href="{=\'javascript:foo\'}"></a>'),
	);
});


test('multiple attributes combined', function () use ($latte) {
	$template = '
		<input
			type="text"
			disabled={=true}
			placeholder={="Enter value"}
			style={=["color" => "blue", "background" => "white"]}
			class={=["form-control", false, "my-input"]}
			data-obj={=["a"=>1]}
		>
	';
	$expected = '<input type="text" disabled placeholder="Enter value" style="color:blue;background:white" class="form-control my-input" data-obj=\'{"a":1}\'>';

	// Trim white-space for better assertion comparison
	$actual = trim($latte->renderToString($template));
	Assert::same($expected, $actual);
});
