<?php

/**
 * Test: {if}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{if true}
	a
	{elseif $b}
	b
	{elseifset $c}
	c
	{else}
	d
{/if}

--

{if}
	a
{/if true}

--

{if}
	a
	{else}
	d
{/if true}

--

{ifset $a}
	a
	{elseif $b}
	b
	{elseifset $c}
	c
	{else}
	d
{/ifset}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/CoreMacros.if2.phtml',
	$latte->compile($template)
);
