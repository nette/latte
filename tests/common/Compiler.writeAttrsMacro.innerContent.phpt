<?php

declare(strict_types=1);

use Latte\Macro;
use Latte\MacroNode;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class TestMacro implements Macro
{
	public $delimiter;


	public function __construct($delimiter)
	{
		$this->delimiter = $delimiter;
	}


	public function initialize()
	{
	}


	public function finalize()
	{
	}


	public function nodeOpened(MacroNode $node)
	{
	}


	public function nodeClosed(MacroNode $node)
	{
		$node->innerContent = $this->delimiter[0] . $node->innerContent . $this->delimiter[1];
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$latte->addMacro('one', new TestMacro('[]'));
$latte->addMacro('two', new TestMacro('{}'));


Assert::match(
	'%A%<div>[]</div>%A%',
	$latte->compile('<div n:one></div>')
);

Assert::match(
	'%A%<div>
[<br>
]</div>%A%',
	$latte->compile("<div n:one>\n<br>\n</div>")
);

Assert::match(
	'%A%<div>
{[<br>
]}</div>%A%',
	$latte->compile("<div n:one n:two>\n<br>\n</div>")
);

// ignore innerContent
Assert::match(
	'%A%<div>@</div>%A%',
	$latte->compile('<div n:inner-one>@</div>')
);

// ignore innerContent
Assert::match(
	'%A%<div>@</div>%A%',
	$latte->compile('<div n:tag-one>@</div>')
);
