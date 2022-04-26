<?php

/**
 * Test: Latte\Engine & parseError
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
Assert::exception(
	fn() => $latte->render('{php * }'),
	ParseError::class,
	'syntax error, unexpected %a%',
);

$latte = new Latte\Engine;
$latte->setTempDirectory(getTempDir());
$latte->setLoader(new Latte\Loaders\StringLoader);
Assert::exception(
	fn() => $latte->render('{php * * }'),
	ParseError::class,
	'syntax error, unexpected %a%',
);
