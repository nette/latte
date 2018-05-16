<?php

/**
 * Test: Empty macros.
 */

use Latte\Compiler;
use Latte\Engine;
use Latte\Loaders\StringLoader;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class EnhancedEngine extends Engine
{
	private $compiler;


	public function getCompiler()
	{
		if (!$this->compiler) {
			$this->compiler = new Compiler();
		}

		return $this->compiler;
	}
}

// With no macros
test(function () {
	$latte = new EnhancedEngine();
	$latte->setLoader(new StringLoader);
	Assert::equal('foo', $latte->renderToString('foo'));
});

// With {=} macro
test(function () {
	$latte = new EnhancedEngine();
	$latte->setLoader(new StringLoader);
	$set = new MacroSet($latte->getCompiler());
	$set->addMacro('=', function (MacroNode $node, PhpWriter $writer) {
		return $writer->write('echo %modify(%node.args)');
	});

	Assert::equal('bar', $latte->renderToString('{$foo}', ['foo' => 'bar']));
	Assert::equal('bar', $latte->renderToString('{=$foo}', ['foo' => 'bar']));
});
