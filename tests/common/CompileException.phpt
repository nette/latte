<?php

/**
 * Test: Compile errors.
 */

declare(strict_types=1);

use Latte\SourceReference;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

// file
$e = Assert::exception(
	fn() => $latte->compile('templates/error.latte'),
	Latte\CompileException::class,
);
Assert::equal(new SourceReference(
	name: 'templates' . DIRECTORY_SEPARATOR . 'error.latte',
	line: 1,
	column: 2,
	code: '{',
), $e->getSource());


// name
$latte->setLoader(new Latte\Loaders\StringLoader(['error' => '{']));
$e = Assert::exception(
	fn() => $latte->compile('error'),
	Latte\CompileException::class,
);
Assert::equal(new SourceReference(
	name: 'error',
	line: 1,
	column: 2,
	code: '{',
), $e->getSource());


// source code
$latte->setLoader(new Latte\Loaders\StringLoader);
$e = Assert::exception(
	fn() => $latte->compile("{* \n'abc}"),
	Latte\CompileException::class,
);
Assert::equal(new SourceReference(
	name: null,
	line: 2,
	column: 6,
	code: "{* \n'abc}",
), $e->getSource());
