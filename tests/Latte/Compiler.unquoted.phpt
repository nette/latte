<?php

/**
 * Test: Latte\Engine: unquoted attributes.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/Compiler.unquoted.attrs.phtml',
	$latte->compile(__DIR__ . '/templates/unquoted.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/Compiler.unquoted.attrs.html',
	$latte->renderToString(
		__DIR__ . '/templates/unquoted.latte',
		array('x' => '\' & "')
	)
);
