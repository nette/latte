<?php

/**
 * Test: {import ...}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{import "inc"}
		{include test}
	',
	'inc' => '
		outer text
		{define test}
			Test block
		{/define}
	',
]));

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.import.phtml',
	$latte->compile('main')
);
Assert::match(
	'Test block',
	trim($latte->renderToString('main'))
);
