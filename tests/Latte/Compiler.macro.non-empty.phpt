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
		$node->empty = false;
		// should be replaced by nodeClosed()
		$node->openingCode = 'ERROR';
		$node->closingCode = 'ERROR';
		$node->attrCode = 'ERROR';
	}


	public function nodeClosed(MacroNode $node)
	{
		$node->openingCode = 'opening';
		$node->closingCode = 'closing';
		$node->attrCode = ' attr';
		$node->content = '[' . $node->content . ']';
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$latte->addMacro('one', new TestMacro);


Assert::match(
	'%A%opening[]closing%A%',
	$latte->compile('{one/}')
);

Assert::match(
	'%A%opening[]closing%A%',
	$latte->compile('{one}{/one}')
);

Assert::match(
	'%A%opening[@]closing%A%',
	$latte->compile('{one}@{/one}')
);

Assert::match(
	'%A%opening[<div attr></div>]closing%A%',
	$latte->compile('<div n:one></div>')
);

Assert::match(
	'%A%opening[<div attr>@</div>]closing%A%',
	$latte->compile('<div n:one>@</div>')
);

Assert::match(
	'%A%<div attr>opening[@]closing</div>%A%',
	$latte->compile('<div n:inner-one>@</div>')
);

Assert::match(
	'%A%opening[<div>]closing@opening[</div>]closing%A%',
	$latte->compile('<div n:tag-one>@</div>')
);
