<?php

/**
 * Test: general snippets test.
 */

use Nette\Bridges\ApplicationLatte\UIMacros;
use Nette\Utils\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/macros.snippet.phtml',
	$latte->compile(__DIR__ . '/templates/snippet.latte')
);
