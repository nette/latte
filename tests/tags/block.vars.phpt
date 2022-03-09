<?php

/**
 * Test: variable scope
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'XX'
{var $var = a}

{define a}
	{$var}
	{var $var = define}
{/define}

{$var}


{block b}
	{$var}
	{var $var = blocknamed}
{/block}

{$var}


{block|trim}
	{$var}
	{var $var = blockmod}
{/block}

{$var}


{block}
	{$var}
	{var $var = block}
{/block}

{$var}
XX;

Assert::matchFile(
	__DIR__ . '/expected/block.vars.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/block.vars.html',
	$latte->renderToString($template)
);
