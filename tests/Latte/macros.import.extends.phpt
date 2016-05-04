<?php

/**
 * Test: Latte\Engine: {import ...}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setTempDirectory(TEMP_DIR);
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
		{import "inc"}
		{include test}
	',
	'inc' => '
		{extends extends}
		outer text
		{define test}Child {include parent}{/define}
	',
	'extends' => '
		outer text
		{define test}Parent{/define}
	',
]));

Assert::match(
	'Child Parent',
	trim($latte->renderToString('main'))
);
