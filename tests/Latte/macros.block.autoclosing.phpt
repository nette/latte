<?php

/**
 * Test: Latte\Engine: {block} autoclosing
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'Block',
	$latte->renderToString('{block}Block')
);
