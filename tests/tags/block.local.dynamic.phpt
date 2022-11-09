<?php

/**
 * Test: local blocks
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;


$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
	{var $var = 10}

	{block local static}
		Static block #{$var}
	{/block}


	{foreach [dynamic, static] as $name}
		{block local $name}
			Dynamic block #{$var}
		{/block}
	{/foreach}

	{include dynamic var => 20}

	{include static var => 30}

	{include #$name . '', var => 40}

	{block local "word$name"}<div n:if="false"></div>{/block}

	{block local "strip$name"|striptags}<span>hello</span>{/block}

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/block.local.dynamic.php',
	$latte->compile($template),
);
Assert::matchFile(
	__DIR__ . '/expected/block.local.dynamic.html',
	$latte->renderToString($template),
);
