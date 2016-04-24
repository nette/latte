<?php

use Latte\IMacro;
use Latte\MacroNode;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class TestMacro implements IMacro
{
	function initialize() {}

	function finalize() {}

	function nodeOpened(MacroNode $node)
	{}

	function nodeClosed(MacroNode $node)
	{
		$node->innerContent = '[' . $node->innerContent . ']';
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$latte->addMacro('one', new TestMacro);


Assert::match(
	'%A%<div>[]</div>%A%',
	$latte->compile('<div n:one></div>')
);

Assert::match(
	"%A%<div>\n[<br>\n]</div>%A%",
	$latte->compile("<div n:one>\n<br>\n</div>")
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
