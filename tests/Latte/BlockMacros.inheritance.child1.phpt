<?php

/**
 * Test: {extends ...} test I.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => file_get_contents(__DIR__ . '/templates/BlockMacros.parent.latte'),

	'main' => '
{extends "parent"}

{import "inc"}
{include "inc" with blocks}

{block title}Homepage | {include parent}{include parent}{/block}

{block content}
	<ul>
	{foreach $people as $person}
		<li>{$person}</li>
	{/foreach}
	</ul>
	Parent: {gettype($this->getReferringTemplate())}
{/block}
	',

	'inc' => '{define test /}',
]));

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.inheritance.child1.phtml',
	$latte->compile('main')
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.inheritance.child1.html',
	$latte->renderToString('main', ['people' => ['John', 'Mary', 'Paul']])
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.inheritance.child1.parent.phtml',
	$latte->compile('parent')
);
