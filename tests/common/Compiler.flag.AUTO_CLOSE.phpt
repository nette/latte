<?php

declare(strict_types=1);

use Latte\Macro;
use Latte\MacroNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestMacro implements Macro
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
$latte->getCompiler()->addMacro('test', new TestMacro, Macro::AUTO_CLOSE);

Assert::match(
	'%A%openingclosing%A%',
	$latte->compile('{test}')
);
