<?php

/**
 * Test: Latte\CoreMacros: {varType type $var}
 */

declare(strict_types=1);

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

test(function () use ($compiler) {
	Assert::same('<?php /** @var string $var */ ?>', $compiler->expandMacro('varType', 'string', '$var')->openingCode);
	Assert::same('<?php /** @var ExampleClass|null $var */ ?>', $compiler->expandMacro('varType', 'ExampleClass|null', '$var')->openingCode);

	Assert::exception(function () use ($compiler) {
		$compiler->expandMacro('varType', 'string');
	}, Latte\CompileException::class, 'Missing variable type or name in {varType}');

	Assert::exception(function () use ($compiler) {
		$compiler->expandMacro('varType', '');
	}, Latte\CompileException::class, 'Missing variable type or name in {varType}');
});
