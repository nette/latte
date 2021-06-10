<?php

/**
 * Test: {include parent}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

// out of block
Assert::exception(function () use ($latte) {
	$latte->renderToString('{include parent}');
}, Latte\CompileException::class, 'Cannot include parent block outside of any block.');

// in anonymous block
Assert::exception(function () use ($latte) {
	$latte->renderToString('{block} {include parent} {/block}');
}, Latte\CompileException::class, 'Cannot include parent block outside of any block.');

// in snippet block
Assert::exception(function () use ($latte) {
	$latte->renderToString('{snippet foo} {include parent} {/snippet}');
}, Latte\CompileException::class, 'Cannot include parent block outside of any block.');


$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => '{extends "parent"} {block foo}-{include parent|trim}-{/block}',
	'main2' => '{extends "parent"} {block foo} {include parent, i: 10} {/block}',
	'main3' => '{extends "parent"} {block foo} {include parent} {include parent} {/block}',
	'parent' => '{block foo} parent {$i ?? ""} {/block}',
]));

// with modifier
Assert::match(
	'- parent  -',
	$latte->renderToString('main1')
);

// with params
Assert::match(
	'  parent 10',
	$latte->renderToString('main2')
);

// double
Assert::match(
	'  parent    parent',
	$latte->renderToString('main3')
);
