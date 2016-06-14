<?php

/**
 * Test: {includeblock ...}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '

{includeblock "inc"}

{include test}
	',

	'inc' => '
{define test}
	Parent: {basename($this->getReferringTemplate()->getName())}/{$this->getReferenceType()}
{/define}
	',
]));

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.includeblock.phtml',
	@$latte->compile('main') // @ false temporary warning for {includeblock}
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.includeblock.html',
	@$latte->renderToString('main') // @ false temporary warning for {includeblock}
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.includeblock.inc.phtml',
	$latte->compile('inc')
);
