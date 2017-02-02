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
	{
		// should not be replaced by nodeClosed()
		$node->openingCode = 'opening';
		$node->closingCode = 'closing';
		$node->attrCode = ' attr';
		$node->empty = TRUE;
	}

	function nodeClosed(MacroNode $node)
	{
		$node->openingCode = 'ERROR';
		$node->closingCode = 'ERROR';
		$node->attrCode = 'ERROR';
		$node->content = 'ERROR';
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$latte->addMacro('one', new TestMacro);


Assert::match(
	"%A%opening<?%A%",
	$latte->compile('{one}')
);

Assert::match(
	'%A%opening<div attr></div>%A%',
	$latte->compile('<div n:one></div>')
);

Assert::match(
	"%A%opening<div attr>@</div><?%A%",
	$latte->compile('<div n:one>@</div>')
);

Assert::exception(function () use ($latte) {
	$latte->compile('<div n:inner-one>@</div>');
}, Latte\CompileException::class, 'Unable to use empty macro as n:inner-one.');

Assert::exception(function () use ($latte) {
	$latte->compile('<div n:tag-one>@</div>');
}, Latte\CompileException::class, 'Unable to use empty macro as n:tag-one.');
