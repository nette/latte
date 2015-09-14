<?php

/**
 * Test: Latte\Engine: {extends ...} test VI.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/macros.inheritance.child6.phtml',
	$latte->compile(__DIR__ . '/templates/inheritance.child6.latte')
);
Assert::same(
	"1\n",
	$latte->renderToString(__DIR__ . '/templates/inheritance.child6.latte')
);
