<?php

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.snippet4.phtml',
	$latte->compile(__DIR__ . '/templates/snippets.block.latte')
);
