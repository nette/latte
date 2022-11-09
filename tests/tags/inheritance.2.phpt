<?php

/**
 * Test: {extends ...} test II.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => file_get_contents(__DIR__ . '/templates/parent.latte'),

	'main' => <<<'XX'

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

		XX,
]));

Assert::matchFile(
	__DIR__ . '/expected/inheritance.2.php',
	$latte->compile('main'),
);
Assert::matchFile(
	__DIR__ . '/expected/inheritance.2.html',
	$latte->renderToString('main', ['people' => ['John', 'Mary', 'Paul']]),
);
