<?php

/**
 * Test: general snippets test.
 */

use Nette\Utils\Html;
use Nette\Bridges\ApplicationLatte\UIMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/macros.snippet.alt.phtml',
	$latte->compile(__DIR__ . '/templates/snippet.alt.latte')
);
