<?php

/**
 * Test: {block $name} dynamic blocks.
 */

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

{block word$name}{/block}
{block "word$name"}{/block}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/macros.dynamicblock.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/macros.dynamicblock.html',
	$latte->renderToString($template)
);
