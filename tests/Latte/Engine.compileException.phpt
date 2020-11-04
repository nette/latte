<?php

/**
 * Test: Latte\Engine & compileException
 */

declare(strict_types=1);

use Latte\CompileException;
use Latte\Macros\MacroSet;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);

	$set = new MacroSet($latte->getCompiler());
	$set->addMacro('exception', function () {
		throw new Exception('Macro exception');
	});

	$latte->render('{exception}');
}, CompileException::class, "Thrown exception 'Macro exception'");
