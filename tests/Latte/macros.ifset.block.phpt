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
	$compiler->expandMacro('ifset', '$var');
}, 'Latte\CompileException', 'Unknown macro {ifset $var}');


// {elseifset ... }
Assert::same( '<?php } elseif (isset($_b->blocks["block"])) { ?>',  $compiler->expandMacro('elseifset', '#block')->openingCode );
Assert::same( '<?php } elseif (isset($item->var["#test"], $_b->blocks["block"])) { ?>',  $compiler->expandMacro('elseifset', '$item->var["#test"], #block')->openingCode );
