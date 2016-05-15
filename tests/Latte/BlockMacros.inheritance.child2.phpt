<?php

/**
 * Test: {extends ...} test II.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => file_get_contents(__DIR__ . '/templates/inheritance.parent.latte'),

	'main' => '
{extends "parent"}

{block content}
	<h1>{block title}Homepage {/block}</h1>

	<ul>
	{foreach $people as $person}
		<li>{$person}</li>
	{/foreach}
	</ul>
{/block}

{block sidebar}{/block}
	',
]));

Assert::matchFile(
	__DIR__ . '/expected/macros.inheritance.child2.phtml',
	$latte->compile('main')
);
Assert::matchFile(
	__DIR__ . '/expected/macros.inheritance.child2.html',
	$latte->renderToString('main', ['people' => ['John', 'Mary', 'Paul']])
);
