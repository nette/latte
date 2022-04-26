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
		// should not be replaced by nodeClosed()
		$node->openingCode = 'opening';
		$node->closingCode = 'closing';
		$node->attrCode = ' attr';
		$node->empty = true;
	}


	public function nodeClosed(MacroNode $node)
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
	'%A%opening%A%',
	$latte->compile('{one}'),
);

Assert::match(
	'%A%opening<div attr></div>%A%',
	$latte->compile('<div n:one></div>'),
);

Assert::match(
	'%A%opening<div attr>@</div>%A%',
	$latte->compile('<div n:one>@</div>'),
);

Assert::exception(
	fn() => $latte->compile('<div n:inner-one>@</div>'),
	Latte\CompileException::class,
	'Unexpected prefix in n:inner-one.',
);

Assert::exception(
	fn() => $latte->compile('<div n:tag-one>@</div>'),
	Latte\CompileException::class,
	'Unexpected prefix in n:tag-one.',
);
