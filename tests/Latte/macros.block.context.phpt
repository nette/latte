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
		$latte->renderToString('<meta content="{include #foo|noescape}">{block foo}{$value}{/block}', ['value' => 'b"ar'])
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
