<?php

/**
 * Test: Latte\CoreMacros: {var ...} {default ...}
 */

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

test(function () use ($compiler) { // {var ... }
	Assert::same('<?php $var=NULL; $var2=NULL ?>',  $compiler->expandMacro('var', 'var, var2', '')->openingCode);
	Assert::same('<?php $var = \'hello\' ?>',  $compiler->expandMacro('var', 'var => hello', '')->openingCode);
	Assert::same('<?php $var = \'hello\'; $var2 = \'world\' ?>',  $compiler->expandMacro('var', 'var => hello, var2 = world', '')->openingCode);
	Assert::same('<?php $var = 123 ?>',  $compiler->expandMacro('var', '$var => 123', '')->openingCode);
	Assert::same('<?php $var = 123 ?>',  $compiler->expandMacro('var', '$var = 123', '')->openingCode);
	Assert::same('<?php $var1 = 123; $var2 = "nette framework" ?>',  $compiler->expandMacro('var', 'var1 = 123, $var2 => "nette framework"', '')->openingCode);
	Assert::same('<?php $temp->var1 = 123 ?>',  $compiler->expandMacro('var', '$temp->var1 = 123', '')->openingCode);

	Assert::exception(function () use ($compiler) {
		$compiler->expandMacro('var', '$var => "123', '');
	}, Latte\CompileException::class, 'Unexpected %a% on line 1, column 9.');

	Assert::exception(function () use ($compiler) {
		$compiler->expandMacro('var', '$var => 123', '|filter');
	}, Latte\CompileException::class, 'Modifiers are not allowed here.');
});


test(function () use ($compiler) { // {default ...}
	Assert::same("<?php extract(['var'=>NULL, 'var2'=>NULL], EXTR_SKIP) ?>",  $compiler->expandMacro('default', 'var, var2', '')->openingCode);
	Assert::same("<?php extract(['var' => 'hello'], EXTR_SKIP) ?>",  $compiler->expandMacro('default', 'var => hello', '')->openingCode);
	Assert::same("<?php extract(['var' => 123], EXTR_SKIP) ?>",  $compiler->expandMacro('default', '$var => 123', '')->openingCode);
	Assert::same("<?php extract(['var' => 123], EXTR_SKIP) ?>",  $compiler->expandMacro('default', '$var = 123', '')->openingCode);
	Assert::same("<?php extract(['var1' => 123, 'var2' => \"nette framework\"], EXTR_SKIP) ?>",  $compiler->expandMacro('default', 'var1 = 123, $var2 => "nette framework"', '')->openingCode);

	Assert::exception(function () use ($compiler) {
		$compiler->expandMacro('default', '$temp->var1 = 123', '');
	}, Latte\CompileException::class, "Unexpected '->' in {default \$temp->var1 = 123}");

	Assert::exception(function () use ($compiler) {
		$compiler->expandMacro('default', '$var => 123', '|filter');
	}, Latte\CompileException::class, 'Modifiers are not allowed here.');
});
