<?php

/**
 * Test: {define ...}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

{var $var = 10}

{define test}
	This is definition #{$var}
{/define}

a) {include #test, var => 20}

{define testargs $var1, ?stdClass $var2, \C\B|null $var3}
	Variables {$var1}, {$var2}, {$hello}
{/define}

b) {include testargs, 1}

{define outer}
	{include testargs, hello}
{/define}

g) {include outer}

{var $var1 = outer}
h) {include testargs}

i) {include testargs null}
EOD;

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.defineblock.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.defineblock.html',
	$latte->renderToString($template, ['hello' => 'world'])
);
