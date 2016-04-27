<?php

/**
 * Test: Latte\Engine: block context
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);

	Assert::error(function () use ($latte) {
		$latte->renderToString('<meta content="{include #foo}">{block #foo}{$value}{/block}', ['value' => 'b"ar']);
	}, E_USER_WARNING, 'Incompatible context for including block foo.');

	Assert::error(function () use ($latte) {
		$latte->renderToString('<meta content="aaa{include #foo}aaa">{block #foo}{$value}{/block}', ['value' => 'b"ar']);
	}, E_USER_WARNING, 'Incompatible context for including block foo.');

	Assert::same('<meta content="b"ar">b"ar',
		$latte->renderToString('<meta content="{include #foo|nocheck}">{block foo}{$value}{/block}', ['value' => 'b"ar'])
	);

	Assert::same('<meta content="b&quot;ar">b"ar',
		$latte->renderToString('<meta content="{include #foo|escape}">{block foo}{$value}{/block}', ['value' => 'b"ar'])
	);

	Assert::same('<meta content="b&quot;ar"><meta content="b&quot;ar">',
		$latte->renderToString('<meta content="{block foo}{$value}{/block}"><meta content="{include #foo}">', ['value' => 'b"ar'])
	);

	Assert::same('<meta content="b&quot;ar"><meta content="b&quot;ar">',
		$latte->renderToString('<meta content={block foo}{$value}{/block}><meta content={include #foo}>', ['value' => 'b"ar'])
	);

	Assert::same('foo<div>foo</div>',
		$latte->renderToString('{block main}foo{/block}<div>{include #main}</div>')
	);

	Assert::same('<div>foo</div><div>foo</div>',
		$latte->renderToString('{include #main}{block main}<div>foo</div>{/block}')
	);
});


test(function () {
	$latte = new Latte\Engine;

	Assert::match('<meta name="b&quot;ar">', $latte->renderToString(__DIR__ . '/templates/block.context.1.latte', ['foo' => 'b"ar']));

	Assert::match('<meta name="b&quot;ar">', $latte->renderToString(__DIR__ . '/templates/block.context.2.latte', ['foo' => 'b"ar']));

	Assert::error(function () use ($latte) {
		$latte->renderToString(__DIR__ . '/templates/block.context.3.latte', ['foo' => 'b"ar']);
	}, E_USER_WARNING, 'Overridden block foo in an incompatible context.');

	Assert::error(function () use ($latte) {
		$latte->renderToString(__DIR__ . '/templates/block.context.4.latte', ['foo' => 'b"ar']);
	}, E_USER_WARNING, 'Overridden block foo in an incompatible context.');
});


test(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);

	Assert::match(
		"<div><h1>title</h1></div> <h1>title</h1>",
		$latte->renderToString('<div>{include title}</div> <h1 n:block=title>title</h1>')
	);

	Assert::match(
		"<div><style>...</style></div> <style>...</style>",
		$latte->renderToString('<div>{include style}</div> <style n:block=style>...</style>')
	);

	Assert::error(function () use ($latte) {
		$latte->renderToString('<div>{include style}</div> <STYLE n:inner-block=style>...</STYLE>');
	}, E_USER_WARNING, 'Incompatible context for including block style.');

	Assert::match(
		"<div><script>...</script></div> <script>...</script>",
		$latte->renderToString('<div>{include script}</div> <script n:block=script>...</script>')
	);

	Assert::error(function () use ($latte) {
		$latte->renderToString('<div>{include script}</div> <script n:inner-block=script>...</script>');
	}, E_USER_WARNING, 'Incompatible context for including block script.');

});
