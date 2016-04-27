<?php

/**
 * Test: Latte\Engine and blocks.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match('', $latte->renderToString('{define foobar}Hello{/define}'));

Assert::match('', $latte->renderToString('{define foo-bar}Hello{/define}'));

Assert::match('', $latte->renderToString('{define $foo}Hello{/define}', ['foo' => 'bar']));

// code optimization in BlockMacros vs. variables in placeholders
Assert::match(
	'<br class="123">',
	$latte->renderToString('{block test}<br n:class="$var">{/block}', ['var' => 123])
);
