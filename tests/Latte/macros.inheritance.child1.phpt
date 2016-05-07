<?php

/**
 * Test: Latte\Engine: {extends ...} test I.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => file_get_contents(__DIR__ . '/templates/inheritance.parent.latte'),

	'main' => '
{extends "parent"}

{import "inc"}
{includeblock "inc"}

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
	__DIR__ . '/expected/macros.inheritance.child1.child.phtml',
	@$latte->compile('main') // @ false temporary warning for {includeblock}
);
Assert::matchFile(
	__DIR__ . '/expected/macros.inheritance.child1.html',
	@$latte->renderToString('main', ['people' => ['John', 'Mary', 'Paul']]) // @ false temporary warning for {includeblock}
);
Assert::matchFile(
	__DIR__ . '/expected/macros.inheritance.child1.parent.phtml',
	$latte->compile('parent')
);
