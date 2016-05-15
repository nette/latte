<?php

/**
 * Test: dynamic snippets test.
 */

use Nette\Bridges\ApplicationLatte\UIMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.dynamicsnippets.phtml',
	$latte->compile(__DIR__ . '/templates/dynamicsnippets.latte')
);
