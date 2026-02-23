<?php declare(strict_types=1);

/**
 * Test: {block} autoclosing
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::match(
	'Block',
	$latte->renderToString('{block}Block'),
);

Assert::exception(
	fn() => $latte->renderToString('{block}{block}Block'),
	Latte\CompileException::class,
	'Unexpected end, expecting {/block} (on line 1 at column 20)',
);
