<?php

/**
 * Test: Latte\Macros\BlockMacros {ifset #block}
 */

use Latte\Macros\BlockMacros,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
BlockMacros::install($compiler);

// {ifset ... }
Assert::same( '<?php if (isset($_b->blocks["block"])) { ?>',  $compiler->expandMacro('ifset', '#block')->openingCode );
Assert::same( '<?php if (isset($item->var["#test"], $_b->blocks["block"])) { ?>',  $compiler->expandMacro('ifset', '$item->var["#test"], #block')->openingCode );

Assert::exception(function() use ($compiler) {
	Assert::same( '<?php if (isset($var)) { ?>',  $compiler->expandMacro('ifset', '$var')->openingCode );
}, 'Latte\CompileException', 'Unhandled macro {ifset}');
