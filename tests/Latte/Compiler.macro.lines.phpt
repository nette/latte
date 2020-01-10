<?php

declare(strict_types=1);

use Latte\Macro;
use Latte\MacroNode;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class TestMacro implements Macro
{
	public $empty = true;


	public function initialize()
	{
	}


	public function finalize()
	{
	}


	public function nodeOpened(MacroNode $node)
	{
		$node->empty = $this->empty;
		$node->openingCode = "opening($node->startLine)";
	}


	public function nodeClosed(MacroNode $node)
	{
		$node->closingCode = "closing($node->endLine)";
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$macro = new TestMacro;
$latte->addMacro('one', $macro);


Assert::match(
	'%A%opening(1)%A%',
	$latte->compile('{one}')
);

Assert::match(
	'%A%opening(2)%A%',
	$latte->compile('
		{one
		}')
);

Assert::match(
	'%A%opening(2)%A%',
	$latte->compile('
	<div
	n:one
	></div>
	')
);


$macro->empty = false;

Assert::match(
	'%A%opening(1)closing(1)%A%',
	$latte->compile('{one /}')
);

Assert::match(
	'%A%opening(2)%A%closing(4)%A%',
	$latte->compile('
		{one}

		{/
		}')
);

Assert::match(
	'%A%opening(2)%A%closing(5)%A%',
	$latte->compile('
	<div
	n:one
	>
	</div
	>
	')
);
