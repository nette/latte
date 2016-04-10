<?php

/**
 * Test: Latte\Engine & parseError
 * @phpversion < 7
 * @phpversion >= 5.4  exists with bad error code in PHP 5.3
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->render('{php * }');
}, 'Latte\CompileException', "Error in template: syntax error, unexpected '*'");
