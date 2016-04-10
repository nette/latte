<?php

/**
 * Test: Latte\Engine & parseError
 * @phpversion 7
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->render('{php * }');
}, ParseError::class, "syntax error, unexpected '*'");

Assert::exception(function () {
	$latte = new Latte\Engine;
	$latte->setTempDirectory(TEMP_DIR);
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->render('{php * * }');
}, ParseError::class, "syntax error, unexpected '*'");
