<?php

/**
 * Test: Latte\Engine & compileException
 */

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
}, 'Latte\CompileException', "Thrown exception 'Macro exception'");
