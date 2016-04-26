<?php

/**
 * Test: Latte\Engine: n:macro that behaves implicitly as inner- or tag- prefixed
 */

use Latte\IMacro;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

class TestMacros extends MacroSet
{
	public static function install(Latte\Compiler $compiler)
	{
		$me = new static($compiler);
		$me->addMacro('test.inner',
			//begin
			function (MacroNode $node, PhpWriter $writer) {
				return '?>{<?php';
			},
			//end
			function (MacroNode $node, PhpWriter $writer) {
				return '?>}<?php';
			},
			//n
			NULL,
			//flags
			IMacro::IMPLICIT_INNER
		);
		$me->addMacro('test.tag',
			//begin
			function (MacroNode $node, PhpWriter $writer) {
				return '?>[<?php';
			},
			//end
			function (MacroNode $node, PhpWriter $writer) {
				return '?>]<?php';
			},
			//n
			NULL,
			//flags
			IMacro::IMPLICIT_TAG
		);
	}
}

$latte = new Latte\Engine;
TestMacros::install($latte->getCompiler());

Assert::matchFile(
	__DIR__ . '/expected/macros.implicit-prefix.html',
	$latte->renderToString(__DIR__ . '/templates/macros.implicit-prefix.latte')
);
