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
	$latte->renderToString('{block}Block'),
);

Assert::exception(
	fn() => $latte->renderToString('{block}{block}Block'),
	Latte\CompileException::class,
	'Unexpected end, expecting {/block} (on line 1 at column 20)',
);
