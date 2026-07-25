<?php declare(strict_types=1);

/**
 * Test: {layout file, args} & {extends file, args} explicit variables for parent template
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('arguments are passed to the layout', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'layout' => 'foo={$foo} {include content}',
		'main' => '{layout "layout", foo: 123}{block content}child{/block}',
	]));
	Assert::same('foo=123 child', $latte->renderToString('main'));
});


test('argument beats template parameter of the same name, blocks keep the original', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'layout' => 'foo={$foo} {include content}',
		'main' => '{extends "layout", foo: "explicit"}{block content}{$foo}{/block}',
	]));
	Assert::same('foo=explicit param', $latte->renderToString('main', ['foo' => 'param']));
});


test('arguments are not created in the child template', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'layout' => '{include content}',
		'main' => '{layout "layout", foo: 123}{block content}{$foo ?? "undefined"}{/block}',
	]));
	Assert::same('undefined', $latte->renderToString('main'));
});


test('{layout auto} with arguments', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'layout' => 'foo={$foo} {include content}',
		'main' => '{layout auto, foo: 123}{block content}child{/block}',
	]));
	$latte->addProvider('coreParentFinder', fn() => 'layout');
	Assert::same('foo=123 child', $latte->renderToString('main'));
});


test('{layout none} with arguments is forbidden', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	Assert::exception(
		fn() => $latte->compile('{layout none, foo: 123}'),
		Latte\CompileException::class,
		'{layout none} cannot have arguments%a%',
	);
});


test('comma before arguments is required', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	Assert::exception(
		fn() => $latte->compile('{layout "layout" foo: 123}'),
		Latte\CompileException::class,
		'Unexpected %a%',
	);
});
