<?php
declare(strict_types=1);

use Latte\IMacro;
use Latte\MacroNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestMacro implements IMacro
{
	public $inHead;
	public $compiler;


	function initialize()
	{
		$this->inHead = NULL;
	}


	function finalize()
	{
	}


	function nodeOpened(MacroNode $node)
	{
		$node->empty = TRUE;
		$this->inHead = $this->compiler->isInHead();
	}


	function nodeClosed(MacroNode $node)
	{
	}
}


test(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);

	$macro = new TestMacro;
	$macro->compiler = $latte->getCompiler();
	$latte->getCompiler()->addMacro('test_head', $macro);

	$latte->compile('{test_head}');
	Assert::false($macro->inHead);
});


test(function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);

	$macro = new TestMacro;
	$macro->compiler = $latte->getCompiler();
	$latte->getCompiler()->addMacro('test_head', $macro, IMacro::ALLOWED_IN_HEAD);

	$latte->compile('{test_head}');
	Assert::true($macro->inHead);

	$latte->compile('{test_head} hello');
	Assert::true($macro->inHead);

	$latte->compile(' {test_head}  {test_head}');
	Assert::true($macro->inHead);

	$latte->compile('hello {test_head}');
	Assert::false($macro->inHead);

	$latte->compile('{if true}{/if}{test_head}');
	Assert::false($macro->inHead);
});
