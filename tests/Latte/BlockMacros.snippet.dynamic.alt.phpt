<?php

/**
 * Test: dynamic snippets test.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/macros.snippet.dynamic.alt.phtml',
	$latte->compile(__DIR__ . '/templates/snippet.dynamic.alt.latte')
);
