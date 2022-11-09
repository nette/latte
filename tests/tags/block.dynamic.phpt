<?php

/**
 * Test: {block $name} dynamic blocks.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
	{var $var = 10}

	{block static}
		Static block #{$var}
	{/block}


	{foreach [dynamic, static] as $name}
		{block $name}
			Dynamic block #{$var}
		{/block}
	{/foreach}

	{include dynamic var => 20}

	{include static var => 30}

	{include #$name . '', var => 40}

	{block "word$name"}<div n:if="false"></div>{/block}

	{block "strip$name"|striptags}<span>hello</span>{/block}

	{block rand() < 5 ? a : b} expression {/block}
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/block.dynamic.php',
	$latte->compile($template),
);
Assert::matchFile(
	__DIR__ . '/expected/block.dynamic.html',
	$latte->renderToString($template),
);
