<?php declare(strict_types=1);

/**
 * Test: {include parent}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

// out of block
Assert::exception(
	fn() => $latte->renderToString('{include parent}'),
	Latte\CompileException::class,
	'Cannot include parent block outside of any block (on line 1 at column 1)',
);

// in anonymous block
Assert::exception(
	fn() => $latte->renderToString('{block} {include parent} {/block}'),
	Latte\CompileException::class,
	'Cannot include parent block outside of any block (on line 1 at column 9)',
);


$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => '{extends "parent"} {block foo}-{include parent|trim}-{/block}',
	'main2' => '{extends "parent"} {block foo} {include parent, i: 10} {/block}',
	'main3' => '{extends "parent"} {block foo} {include parent} {include parent} {/block}',
	'parent' => '{block foo} parent {$i ?? ""} {/block}',
]));

// with modifier
Assert::exception(
	fn() => $latte->renderToString('main1'),
	Latte\CompileException::class,
	'Filters are not allowed in {include parent} (on line 1 at column 32)',
);

// with params
Assert::match(
	'  parent 10',
	$latte->renderToString('main2'),
);

// double
Assert::match(
	'  parent    parent',
	$latte->renderToString('main3'),
);


$latte->setLoader(new Latte\Loaders\StringLoader([
	'grandparent' => '{block foo}G{/block}',
	'parent' => '{extends "grandparent"}{block foo}{if $n}{include foo, n: $n - 1}{/if}P{$n}{include parent}{/block}',
	'main' => '{extends "parent"}{block foo}C{include parent}{/block}',
	'failing' => '{block foo}{do $fail()}F{/block}',
	'retry' => '{extends "failing"}{block foo}{try}{include parent}{/try}{include parent}{/block}',
]));

// recursion through the same block
Assert::same(
	'CCP0GP1G',
	$latte->renderToString('main', ['n' => 1]),
);

// parent block is available again after it has thrown
$calls = 0;
Assert::same(
	'F',
	$latte->renderToString('retry', ['fail' => function () use (&$calls) {
		if (!$calls++) {
			throw new RuntimeException;
		}
	}]),
);
