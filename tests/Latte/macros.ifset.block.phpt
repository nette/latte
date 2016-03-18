<?php

/**
 * Test: Latte\Macros\BlockMacros {ifset #block}
 */

use Latte\Macros\BlockMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
BlockMacros::install($compiler);

// {ifset ... }
Assert::same('<?php if (isset($_b->blocks["block"])) { ?>',  $compiler->expandMacro('ifset', '#block')->openingCode);
Assert::same('<?php if (isset($_b->blocks["block"])) { ?>',  $compiler->expandMacro('ifset', 'block')->openingCode);
Assert::same('<?php if (isset($_b->blocks["block"], $item->var["#test"])) { ?>',  $compiler->expandMacro('ifset', '#block, $item->var["#test"]')->openingCode);
Assert::same(
	'<?php if (isset($_b->blocks["block1"], $_b->blocks["block2"], $var3, item(\'abc\'))) { ?>',
	$compiler->expandMacro('ifset', '#block1, block2, $var3, item(abc)')->openingCode
);

Assert::exception(function () use ($compiler) {
	$compiler->expandMacro('ifset', '$var');
}, 'Latte\CompileException', 'Unknown macro {ifset $var}');


// {elseifset ... }
Assert::same('<?php } elseif (isset($_b->blocks["block"])) { ?>',  $compiler->expandMacro('elseifset', '#block')->openingCode);
Assert::same('<?php } elseif (isset($_b->blocks["block"])) { ?>',  $compiler->expandMacro('elseifset', 'block')->openingCode);
Assert::same('<?php } elseif (isset($_b->blocks["block"], $item->var["#test"])) { ?>',  $compiler->expandMacro('elseifset', '#block, $item->var["#test"]')->openingCode);
Assert::same(
	'<?php } elseif (isset($_b->blocks["block1"], $_b->blocks["block2"], $var3, item(\'abc\'))) { ?>',
	$compiler->expandMacro('elseifset', '#block1, block2, $var3, item(abc)')->openingCode
);
