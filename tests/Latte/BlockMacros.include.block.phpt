<?php

/**
 * Test: {include file}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => '{define block}[block {$var}]{/} before {include block, var => 1} after',
	'main2' => '{define block}[block {$var}]{/} before {include #block, var => 1} after',
	'main3' => '{define block-2}[block {$var}]{/} before {include block-2, var => 1} after',
	'main4' => '{define block.2}[block {$var}]{/} before {include block.2, var => 1} after',
	'main5' => '{define block.2}[block {$var}]{/} before {include #block.2, var => 1} after',
	'main6' => '{define block.2}[block {$var}]{/} before {include block block.2, var => 1} after',
	'main7' => '{define block}[block {$var}]{/define} {var $name = block} before {include block $name, var => 1} after',

	'main8' => '{define block}<b>block {$var}</b>{/} before {include block, var => 1|striptags} after',
	'main9' => '{define block}block {$var}{/} before {include block true ? "block", var => 2} after',
]));

Assert::match(
	' before [block 1] after',
	$latte->renderToString('main1')
);

Assert::match(
	' before [block 1] after',
	$latte->renderToString('main2')
);

Assert::match(
	' before [block 1] after',
	$latte->renderToString('main3')
);

Assert::exception(function () use ($latte) {
	$latte->renderToString('main4');
}, Latte\RuntimeException::class, "Missing template 'block.2'.");

Assert::match(
	' before [block 1] after',
	$latte->renderToString('main5')
);

Assert::match(
	' before [block 1] after',
	$latte->renderToString('main6')
);

Assert::match(
	'  before [block 1] after',
	$latte->renderToString('main7')
);

Assert::match(
	' before block 1 after',
	$latte->renderToString('main8')
);

Assert::match(
	' before block 2 after',
	$latte->renderToString('main9')
);
