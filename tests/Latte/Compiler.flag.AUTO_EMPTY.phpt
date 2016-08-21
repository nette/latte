<?php

use Latte\IMacro;
use Latte\MacroNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestMacro implements IMacro
{
	public $nodes;

	function initialize()
	{
		$this->nodes = [];
	}

	function finalize() {}

	function nodeOpened(MacroNode $node)
	{
		$this->nodes[] = $node;
	}

	function nodeClosed(MacroNode $node) {}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$macro = new TestMacro;
$latte->getCompiler()->addMacro('test_auto', $macro, IMacro::AUTO_EMPTY);
$latte->getCompiler()->addMacro('test_auto2', $macro, IMacro::AUTO_EMPTY);


$latte->compile('{test_auto} ... {/test_auto}');
Assert::same(' ... ', $macro->nodes[0]->content);

$latte->compile('{test_auto}');
Assert::null($macro->nodes[0]->content);

$latte->compile('{test_auto} {if true} {/if} {/test_auto}');
Assert::match('%A% if (true) %A%', $macro->nodes[0]->content);

$latte->compile('{test_auto} {test_auto} ... {/test_auto}');
Assert::null($macro->nodes[0]->content);
Assert::same(' ... ', $macro->nodes[1]->content);

$latte->compile('{test_auto} <div n:test_auto></div>');
Assert::null($macro->nodes[0]->content);
Assert::match('<div%A%</div>', $macro->nodes[1]->content);

Assert::exception(function () use ($latte) {
	$latte->compile('{test_auto} <div n:test_auto>');
}, 'Latte\CompileException', 'Missing </div> for n:test_auto');

Assert::exception(function () use ($latte) {
	$latte->compile('{test_auto} <div n:test_auto></div> {/test_auto}');
}, 'Latte\CompileException', 'Unexpected {/test_auto}');

$latte->compile('{test_auto} ... {/}');
Assert::same(' ... ', $macro->nodes[0]->content);

$latte->compile('{test_auto} {if true} ... {/}');
Assert::null($macro->nodes[0]->content);

$latte->compile('{test_auto} {test_auto2 true} ... {/}');
Assert::null($macro->nodes[0]->content);
Assert::same(' ... ', $macro->nodes[1]->content);

$latte->compile('{test_auto}x{test_auto2 true} ... {/test_auto2}x');
Assert::null($macro->nodes[0]->content);
Assert::same(' ... ', $macro->nodes[1]->content);

$latte->compile('{test_auto}x{test_auto2 true} ... {/test_auto2}x{/}');
Assert::same('x ... x', $macro->nodes[0]->content);
Assert::same(' ... ', $macro->nodes[1]->content);

$latte->compile('{test_auto} {if true} {/} {/}');
Assert::match('%A% if (true) %A%', $macro->nodes[0]->content);

$latte->compile('{test_auto} {if true} {/} {/test_auto}');
Assert::match('%A% if (true) %A%', $macro->nodes[0]->content);

$latte->compile('{test_auto} {if true} {/if} {/}');
Assert::match('%A% if (true) %A%', $macro->nodes[0]->content);

$latte->compile('{test_auto} {test_auto} ... {/}');
Assert::null($macro->nodes[0]->content);
Assert::same(' ... ', $macro->nodes[1]->content);


$latte->compile('{test_auto} {_}...{/}');
Assert::null($macro->nodes[0]->content);

$latte->compile('{test_auto}lorem{_ipsum}dolor{/}');
Assert::match('lorem%a%ipsum%a%dolor', $macro->nodes[0]->content);
