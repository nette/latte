<?php

/**
 * Test: Latte\Compiler and htmlNode.
 */

declare(strict_types=1);

use Latte\Macro;
use Latte\MacroNode;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockMacro implements Macro
{
	public $empty;


	public function initialize()
	{
	}


	public function finalize()
	{
	}


	public function nodeOpened(MacroNode $node)
	{
		Assert::same($this->empty, $node->htmlNode->empty);
	}


	public function nodeClosed(MacroNode $node)
	{
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$macro = new MockMacro;
$latte->addMacro('foo', $macro);

$macro->empty = true;
Assert::match('%A%<input>%A%', $latte->compile('<input n:foo>'));
Assert::match('%A%<input />%A%', $latte->compile('<input n:foo />'));
Assert::match('%A%<textarea></textarea>%A%', $latte->compile('<textarea n:foo />'));
Assert::match("%A%<textarea></textarea>\n %A%", $latte->compile("<textarea n:foo />\n "));

$macro->empty = false;
Assert::match('%A%<textarea></textarea>%A%', $latte->compile('<textarea n:foo></textarea>'));


$latte->setContentType($latte::CONTENT_XML);

$macro->empty = true;
Assert::match('%A%<input />%A%', $latte->compile('<input n:foo />'));
Assert::match('%A%<textarea />%A%', $latte->compile('<textarea n:foo />'));
Assert::match("%A%<textarea />\n %A%", $latte->compile("<textarea n:foo />\n "));

$macro->empty = false;
Assert::match('%A%<textarea></textarea>%A%', $latte->compile('<textarea n:foo></textarea>'));

Assert::exception(function () use ($latte) {
	$latte->compile('<input n:foo>');
}, Latte\CompileException::class, 'Unexpected end, expecting </input> for n:foo');
