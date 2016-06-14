<?php

/**
 * Test: {define ...}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{var $var = 10}

{define test}
	This is definition #{$var}
{/define}

{include #test, var => 20}

{define testargs $var1, $var2}
	Variables {$var1}, {$var2}
{/define}

{include testargs, 1}

EOD;

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.defineblock.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.defineblock.html',
	$latte->renderToString($template)
);
