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

Assert::error(function () use ($latte) {
	$latte->renderToString('{block}{block}Block');
}, E_USER_WARNING, 'Missing {/block}');
