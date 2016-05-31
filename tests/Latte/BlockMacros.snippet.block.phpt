<?php

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::matchFile(
	__DIR__ . '/expected/macros.snippet.block.phtml',
	$latte->compile(__DIR__ . '/templates/snippets.block.latte')
);
