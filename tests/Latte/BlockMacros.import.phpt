<?php

/**
 * Test: {import ...}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{import "inc"}
		{include test}
	',
	'main-dynamic' => '
		{import "i" . "nc"}
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

Assert::match(
	'Test block',
	trim($latte->renderToString('main-dynamic'))
);
