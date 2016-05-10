<?php

/**
 * Test: Latte\Engine: {define ...}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.defineblock.phtml',
	$latte->compile(__DIR__ . '/templates/defineblock.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.defineblock.html',
	$latte->renderToString(__DIR__ . '/templates/defineblock.latte')
);
