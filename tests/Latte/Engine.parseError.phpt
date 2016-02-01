<?php

/**
 * Test: Latte\Engine & parseError
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->render('{php * }');
}, 'Latte\CompileException', "Error in template: syntax error, unexpected '*'");

$e = Assert::exception(function () {
	$latte = new Latte\Engine;
	$latte->setTempDirectory(TEMP_DIR);
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->render('{php * * }');
}, 'Latte\CompileException', "Error in template: syntax error, unexpected '*'");
Assert::same(13, $e->sourceLine);

if (PHP_VERSION_ID < 50400) {
	die(0); // otherwise PHP exits with code 255
}
