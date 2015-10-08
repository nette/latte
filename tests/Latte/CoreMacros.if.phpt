<?php

/**
 * Test: Latte\Macros\CoreMacros: {if ...}
 */

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

Assert::same('<?php if (isset($var)) { ?>',  $compiler->expandMacro('ifset', '$var')->openingCode);
Assert::same('<?php if (isset($item->var["test"])) { ?>',  $compiler->expandMacro('ifset', '$item->var["test"]')->openingCode);

Assert::exception(function () use ($compiler) {
	$compiler->expandMacro('if', '$var', '|filter');
}, Latte\CompileException::class, 'Modifiers are not allowed here.');

Assert::exception(function () use ($compiler) {
	$compiler->expandMacro('ifset', '$var', '|filter');
}, Latte\CompileException::class, 'Modifiers are not allowed here.');
