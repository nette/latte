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
		{extends parent}
		outer text
		{define test}Child {include parent}{/define}
	',
	'parent' => '
		outer text
		{define test}Parent{/define}
	',
]));

Assert::match(
	'Child Parent',
	trim($latte->renderToString('main'))
);
