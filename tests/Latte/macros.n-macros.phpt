<?php

/**
 * Test: Latte\Engine: general n:attributes test.
 */

use Latte\Runtime\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

$params['people'] = ['John', 'Mary', 'Paul'];

Assert::matchFile(
	__DIR__ . '/expected/macros.n-macros.phtml',
	$latte->compile(__DIR__ . '/templates/n-macros.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/macros.n-macros.html',
	$latte->renderToString(
		__DIR__ . '/templates/n-macros.latte',
		$params
	)
);
