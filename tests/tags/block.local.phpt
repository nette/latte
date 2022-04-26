<?php

/**
 * Test: local blocks
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


Assert::exception(function () use ($latte) {
	$latte->renderToString('{block local, a}');
}, Latte\CompileException::class, "Unexpected arguments 'a' in {block}");


Assert::same(
	'local		local',
	trim($latte->renderToString('
		{include abc}
		{block local abc}local{/block}
	')),
);

Assert::same(
	'5 4 3 2 1 0',
	trim($latte->renderToString('
		{var $i = 5}
		{block local abc}{$i} {if $i}{include this i: $i - 1}{/if} {/block}
	')),
);


Assert::same(
	'5 4 3 2 1 0',
	trim($latte->renderToString('
		{include abc i: 5}
		{define local abc}{$i} {if $i}{include this i: $i - 1}{/if} {/define}
	')),
);


Assert::same(
	'5',
	trim($latte->renderToString('{var $i = 5} {block local abc}{$i}{/block}')),
);


Assert::same(
	'5',
	trim($latte->renderToString('
		{var $i = 5}
		{include abc}
		{define local abc}{$i}{/define}
	')),
);


Assert::same(
	'5',
	trim($latte->renderToString('
		{define local abc}{$i}{/define}
		{var $i = 5}
		{include abc}
	')),
);


Assert::exception(function () use ($latte) {
	$latte->renderToString('{block local a}local{/block} {block a}classic{/block}');
}, Latte\CompileException::class, "Cannot redeclare block 'a'");


Assert::exception(function () use ($latte) {
	$latte->renderToString('{block local abc}{include parent}{/block}');
}, Latte\RuntimeException::class, "Cannot include undefined parent block 'abc'.");


Assert::exception(function () use ($latte) {
	$latte->renderToString('{block a}local{/block} {block local a}classic{/block}');
}, Latte\CompileException::class, "Cannot redeclare block 'a'");


$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{import "inc"}
		{include a}
	',
	'inc' => '{block local a}{/block}',
]));

Assert::exception(function () use ($latte) {
	$latte->renderToString('main');
}, Latte\RuntimeException::class, "Cannot include undefined block 'a'.");


$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{import "inc"}
		{block local a}{include parent}{/block}
	',
	'inc' => '{block local a}{/block}',
]));

Assert::exception(function () use ($latte) {
	$latte->renderToString('main');
}, Latte\RuntimeException::class, "Cannot include undefined parent block 'a'.");


$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{extends "inc"}
		{block local a}{/block}
	',
	'inc' => '{include a}',
]));

Assert::exception(function () use ($latte) {
	$latte->renderToString('main');
}, Latte\RuntimeException::class, "Cannot include undefined block 'a'.");
