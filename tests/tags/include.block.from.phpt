<?php

/**
 * Test: {include block from file}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => 'before {include bl from inc, var => 1} after',
	'main2' => 'before {include block bl from inc, var => 1} after',
	'main3' => '<div title="{include bl from inc, var => 1}">',
	'main4' => 'before {include block loc from inc} after',
	'main5' => 'before {include block bl from inc.ext, var => 1} after',
	'main6' => '{var $var = 1} {include block bl from inc} after',

	'inc' => '{define bl}<b>block {$var}</b>{/define}  {define local loc}local{/define}',
	'inc.ext' => '{extends inc} {define bl}*{include parent $var}*{/define}',
]));

Assert::match(
	'before <b>block 1</b> after',
	$latte->renderToString('main1')
);

Assert::match(
	'before <b>block 1</b> after',
	$latte->renderToString('main2')
);

Assert::match(
	'<div title="&lt;b&gt;block 1&lt;/b&gt;">',
	$latte->renderToString('main3')
);

Assert::match(
	'before local after',
	$latte->renderToString('main4')
);

Assert::match(
	'before *<b>block 1</b>* after',
	$latte->renderToString('main5')
);

Assert::error(function () use ($latte) {
	$latte->renderToString('main6');
}, E_WARNING, 'Undefined variable%a%');

Assert::matchFile(
	__DIR__ . '/expected/include.block.from.phtml',
	$latte->compile('main5')
);
