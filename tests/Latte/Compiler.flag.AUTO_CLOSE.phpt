<?php

declare(strict_types=1);

use Latte\IMacro;
use Latte\MacroNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestMacro implements IMacro
{
	function initialize() {}

	function finalize() {}

	function nodeOpened(MacroNode $node)
	{
		$node->openingCode = 'opening';
		$node->closingCode = 'closing';
	}

	function nodeClosed(MacroNode $node) {}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->getCompiler()->addMacro('test', new TestMacro, IMacro::AUTO_CLOSE);

Assert::match(
	'%A%openingclosing%A%',
	$latte->compile('{test}')
);
