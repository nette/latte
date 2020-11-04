<?php

/**
 * Test: Latte\Engine & parseError
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->render('{php * }');
}, ParseError::class, 'syntax error, unexpected %a%');

Assert::exception(function () {
	$latte = new Latte\Engine;
	$latte->setTempDirectory(getTempDir());
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->render('{php * * }');
}, ParseError::class, 'syntax error, unexpected %a%');
