<?php

declare(strict_types=1);

use Latte\IMacro;
use Latte\MacroNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestMacro implements IMacro
{
	public function initialize()
	{
	}


	public function finalize()
	{
	}


	public function nodeOpened(MacroNode $node)
	{
		$node->openingCode = 'opening';
		$node->closingCode = 'closing';
	}


	public function nodeClosed(MacroNode $node)
	{
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->getCompiler()->addMacro('test', new TestMacro, IMacro::AUTO_CLOSE);

Assert::match(
	'%A%openingclosing%A%',
	$latte->compile('{test}')
);
