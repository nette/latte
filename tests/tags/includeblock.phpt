<?php

/**
 * Test: {includeblock ...}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
{includeblock "inc"}

{include test}
	',

	'inc' => '
{define test}
	Parent: {basename($this->getReferringTemplate()->getName())}/{$this->getReferenceType()}
{/define}
	',
]));

Assert::matchFile(
	__DIR__ . '/expected/includeblock.phtml',
	$latte->compile('main')
);
Assert::matchFile(
	__DIR__ . '/expected/includeblock.html',
	$latte->renderToString('main')
);
Assert::matchFile(
	__DIR__ . '/expected/includeblock.inc.phtml',
	$latte->compile('inc')
);
