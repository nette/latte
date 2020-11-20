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

Assert::exception(function () use ($latte) {
	$latte->renderToString('main');
}, Latte\RuntimeException::class, 'Imported template cannot use {extends} or {layout}, use {import}');
