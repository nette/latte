<?php

/**
 * Test: Latte\Macros\CoreMacros: {if ...}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}{else if a}{/if}');
}, Latte\CompileException::class, 'Arguments are not allowed in {else}, did you mean {elseif}?');

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}{else a}{/if}');
}, Latte\CompileException::class, 'Arguments are not allowed in {else}');

Assert::exception(function () use ($latte) {
	$latte->compile('{else}');
}, Latte\CompileException::class, 'Unexpected tag {else}');

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}{else}{else}{/if}');
}, Latte\CompileException::class, 'Unexpected tag {else}');

Assert::exception(function () use ($latte) {
	$latte->compile('{elseif a}');
}, Latte\CompileException::class, 'Unexpected tag {elseif}');

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}{else}{elseif a}{/if}');
}, Latte\CompileException::class, 'Unexpected tag {elseif}');

Assert::exception(function () use ($latte) {
	$latte->compile('{if}{elseif a}{/if 1}');
}, Latte\CompileException::class, 'Unexpected tag {elseif}');
