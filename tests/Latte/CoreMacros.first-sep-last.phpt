<?php

/**
 * Test: {first}, {last}, {sep}.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{foreach $people as $person}
	{first}({/first} {$person}{sep}, {/sep} {last}){/last}
{/foreach}


{foreach $people as $person}
	{first 2}({/first} {$person}{sep 2}, {/sep} {last 2}){/last}
{/foreach}


{foreach $people as $person}
	{first 1}({/first} {$person}{sep 1}, {/sep} {last 1}){/last}
{/foreach}


{foreach $people as $person}
	<span n:first=0>(</span> {$person}<span n:sep>, </span> <span n:last>)</span>
{/foreach}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/macros.first-sep-last.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/macros.first-sep-last.html',
	$latte->renderToString($template, ['people' => ['John', 'Mary', 'Paul']])
);
