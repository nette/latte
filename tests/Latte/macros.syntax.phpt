<?php

/**
 * Test: Latte\Engine: {syntax ...}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/macros.syntax.phtml',
	$latte->compile(__DIR__ . '/templates/syntax.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/macros.syntax.html',
	$latte->renderToString(
		__DIR__ . '/templates/syntax.latte',
		['people' => ['John', 'Mary', 'Paul']]
	)
);
