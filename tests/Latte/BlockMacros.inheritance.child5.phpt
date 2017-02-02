<?php

/**
 * Test: {extends ...} test V.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';



$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => file_get_contents(__DIR__ . '/templates/BlockMacros.parent.latte'),

	'main' => '
{extends $ext}

{block content}
	Content
{/block}
	',
]));

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.inheritance.child5.phtml',
	$latte->compile('main')
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.inheritance.child5.html',
	$latte->renderToString('main', ['ext' => 'parent'])
);
