<?php

/**
 * Test: {define ...}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'XX'
{define test $var1, $var2, $var3}
	Variables {$var1}, {$var2}, {$hello}
{/define}

a) {include test, 1}

{define outer}
	{include test, hello}
{/define}

b) {include outer}

{var $var1 = outer}
c) {include test}

d) {include test null}
XX;

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.define.args1.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.define.args1.html',
	$latte->renderToString($template, ['hello' => 'world'])
);


$template = <<<'XX'
{define test $var1, ?stdClass $var2, \C\B|null $var3}
{/define}

{include test, 1}
XX;

Assert::matchFile(
	__DIR__ . '/expected/BlockMacros.define.typehints.phtml',
	$latte->compile($template)
);
