<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


test('text attributes', function () use ($latte) {
	Assert::same(
		'<button title="Hello">Button</button>',
		$latte->renderToString('<button title={="Hello"}>Button</button>'),
	);
});


test('boolean attributes', function () use ($latte) {
	Assert::same(
		'<input disabled>',
		$latte->renderToString('<input disabled={=true}>'),
	);

	Assert::same(
		'<input disabled>',
		$latte->renderToString('<input disabled={=1}>'),
	);

	Assert::same(
		'<input >',
		$latte->renderToString('<input disabled={=false}>'),
	);

	Assert::same(
		'<input >',
		$latte->renderToString('<input required={=null}>'),
	);

	Assert::same(
		'<input >',
		$latte->renderToString('<input required={=0}>'),
	);
});


test('style attribute', function () use ($latte) {
	Assert::same(
		'<div style="color:red;">Box</div>',
		$latte->renderToString('<div style={="color:red;"}>Box</div>'),
	);

	Assert::same(
		'<div style="color:red;font-size:16px">Box</div>',
		$latte->renderToString('<div style={=["color" => "red", "font-size" => "16px"]}>Box</div>'),
	);

	Assert::same(
		'<div >Box</div>',
		$latte->renderToString('<div style={=[]}>Box</div>'),
	);
});


test('class attribute', function () use ($latte) {
	Assert::same(
		'<span class="highlight">Text</span>',
		$latte->renderToString('<span class={="highlight"}>Text</span>'),
	);

	Assert::same(
		'<span class="foo bar">Text</span>',
		$latte->renderToString('<span class={=["foo","",false,"bar"]}>Text</span>'),
	);

	Assert::same(
		'<span >Text</span>',
		$latte->renderToString('<span class={=[]}>Text</span>'),
	);
});


test('data-* attributes', function () use ($latte) {
	Assert::same(
		'<div data-foo="bar"></div>',
		$latte->renderToString('<div data-foo={="bar"}></div>'),
	);

	Assert::same(
		'<div data-info=\'{"user":"karel","age":30}\'></div>',
		$latte->renderToString('<div data-info={=["user" => "karel", "age" => 30]}></div>'),
	);
});
