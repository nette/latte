<?php

/**
 * Test: Latte\Parser errors.
 */

use Tester\Assert;
use Latte\Parser;


require __DIR__ . '/../bootstrap.php';


Assert::exception(function () {
	$parser = new Parser;
	$parser->parse("\xA0\xA0");
}, 'InvalidArgumentException', 'Template is not valid UTF-8 stream.');


Assert::exception(function () use (& $parser) {
	$parser = new Parser;
	$parser->parse("{var \n'abc}");
}, 'Latte\CompileException', 'Malformed macro');
Assert::same(1, $parser->getLine());


Assert::exception(function () use (& $parser) {
	$parser = new Parser;
	$parser->parse("\n{* \n'abc}");
}, 'Latte\CompileException', 'Malformed macro');
Assert::same(2, $parser->getLine());


Assert::exception(function () use (& $parser) {
	$parser = new Parser;
	$parser->parse('{');
}, 'Latte\CompileException', 'Malformed macro');
Assert::same(1, $parser->getLine());
