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

Assert::error(function () use ($compiler) {
	$compiler->expandMacro('if', '$var', '|filter');
}, E_USER_WARNING, 'Modifiers are not allowed in {if}');

Assert::error(function () use ($compiler) {
	$compiler->expandMacro('ifset', '$var', '|filter');
}, E_USER_WARNING, 'Modifiers are not allowed in {ifset}');

Assert::error(function () use ($compiler) {
	$compiler->expandMacro('else', 'if args');
}, E_USER_WARNING, 'Arguments are not allowed in {else}, did you mean {elseif}?');

Assert::error(function () use ($compiler) {
	$compiler->expandMacro('else', 'args');
}, E_USER_WARNING, 'Arguments are not allowed in {else}');
