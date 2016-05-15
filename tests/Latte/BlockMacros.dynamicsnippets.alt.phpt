<?php

/**
 * Test: dynamic snippets test.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.dynamicsnippets.alt.phtml',
	$latte->compile(__DIR__ . '/templates/dynamicsnippets.alt.latte')
);
