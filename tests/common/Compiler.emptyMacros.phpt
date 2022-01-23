<?php

/**
 * Test: Empty macros.
 */

declare(strict_types=1);

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


	public function getCompiler(): Compiler
	{
		if (!$this->compiler) {
			$this->compiler = new Compiler;
		}

		return $this->compiler;
	}
}

test('With no macros', function () {
	$latte = new EnhancedEngine;
	$latte->setLoader(new StringLoader);
	Assert::equal('foo', $latte->renderToString('foo'));
});

test('With {=} macro', function () {
	$latte = new EnhancedEngine;
	$latte->setLoader(new StringLoader);
	$set = new MacroSet($latte->getCompiler());
	$set->addMacro('=', fn(MacroNode $node, PhpWriter $writer) => $writer->write('echo %modify(%node.args)'));

	Assert::equal('bar', $latte->renderToString('{$foo}', ['foo' => 'bar']));
	Assert::equal('bar', $latte->renderToString('{=$foo}', ['foo' => 'bar']));
});
