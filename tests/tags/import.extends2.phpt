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
		{extends parent}
		{block main}
			{include test}
		{/block}
	',
	'parent' => '
		{import inc}
		{include main}
	',
	'inc' => '
		{define test}test block{/define}
	',
]));

Assert::match(
	'test block',
	trim($latte->renderToString('main')),
);
