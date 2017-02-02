<?php

/**
 * Test: {block} autoclosing
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'Block',
	$latte->renderToString('{block}Block')
);
