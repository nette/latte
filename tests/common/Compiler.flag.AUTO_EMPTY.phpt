<?php

declare(strict_types=1);

use Latte\Macro;
use Latte\MacroNode;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TestMacro implements Macro
{
	public $nodes;


	public function initialize()
	{
		$this->nodes = [];
	}


	public function finalize()
	{
	}


	public function nodeOpened(MacroNode $node)
	{
		$this->nodes[] = $node;
	}


	public function nodeClosed(MacroNode $node)
	{
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$macro = new TestMacro;
$latte->getCompiler()->addMacro('test_auto', $macro, Macro::AUTO_EMPTY);


$latte->compile('{test_auto} ... {/test_auto}');
Assert::same(' ... ', $macro->nodes[0]->content);

Assert::error(function () use ($latte) {
	$latte->compile('{test_auto}');
}, E_USER_DEPRECATED, 'Auto-empty behaviour is deprecated, replace {test_auto} with {test_auto /} in line 1');

@$latte->compile('{test_auto}'); // deprecated
Assert::same('', $macro->nodes[0]->content);

$latte->compile('{test_auto} {if true} {/if} {/test_auto}');
Assert::match('%A% if (true) %A%', $macro->nodes[0]->content);

@$latte->compile('{test_auto} {test_auto} ... {/test_auto}'); // deprecated
Assert::same('', $macro->nodes[0]->content);
Assert::same(' ... ', $macro->nodes[1]->content);

@$latte->compile('{test_auto} <div n:test_auto></div>'); // deprecated
Assert::same('', $macro->nodes[0]->content);
Assert::match('<div%A%</div>', $macro->nodes[1]->content);

Assert::exception(function () use ($latte) {
	@$latte->compile('{test_auto} <div n:test_auto>'); // deprecated
}, Latte\CompileException::class, 'Missing </div> for n:test_auto');

Assert::exception(function () use ($latte) {
	@$latte->compile('{test_auto} <div n:test_auto></div> {/test_auto}'); // deprecated
}, Latte\CompileException::class, 'Unexpected {/test_auto}');
