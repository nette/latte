<?php

/**
 * Test: Latte\Engine and blocks.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

// code optimization in BlockMacros vs. variables in placeholders
Assert::match(
	'<br class="123">',
	$latte->renderToString('{block test}<br n:class="$var">{/block}', ['var' => 123]),
);

Assert::exception(function () use ($latte) {
	$latte->renderToString('{block _foobar}Hello{/block}');
}, Latte\CompileException::class, "Block name must start with letter a-z, '_foobar' given.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{block 123}Hello{/block}');
}, Latte\CompileException::class, "Block name must start with letter a-z, '123' given.");
