<?php

/**
 * Test: {trace}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setTempDirectory(getTempDir());
$latte->setLoader(new Latte\Loaders\StringLoader([
	'parent' => '
{block content}
{/block}

{define inc}
	{embed file embed}
		{block foo}
			{trace}
		{/block}
	{/embed}
{/define}
',

	'main' => '
{extends "parent"}

{block content}
	{include inc, var: 123}
{/block}

{block inc}
	{include parent, var2: 456}
{/block}
	',
	'embed' => '{block foo}{/block}',
]));


$e = Assert::exception(function () use ($latte) {
	$latte->render('main');
}, Latte\RuntimeException::class, 'Your location in Latte templates');


Assert::same([
	[
		'function' => '{block foo}',
		'file' => 'parent',
		'line' => 7,
		'args' => [],
	],
	[
		'function' => '{embed embed}',
		'file' => 'parent',
		'line' => 6,
		'args' => [],
	],
	[
		'function' => '{define inc}',
		'file' => 'parent',
		'line' => 5,
		'args' => ['var2' => 456, 'var' => 123],
	],
	[
		'function' => '{include parent}',
		'file' => 'main',
		'line' => 9,
		'args' => ['var2' => 456, 'var' => 123],
	],
	[
		'function' => '{block inc}',
		'file' => 'main',
		'line' => 8,
		'args' => ['var' => 123],
	],
	[
		'function' => '{include inc}',
		'file' => 'main',
		'line' => 5,
		'args' => ['var' => 123],
	],
	[
		'function' => '{block content}',
		'file' => 'main',
		'line' => 4,
		'args' => [],
	],
	[
		'function' => '{extends parent}',
		'file' => 'main',
		'line' => 0,
		'args' => [],
	],
], $e->getTrace());
