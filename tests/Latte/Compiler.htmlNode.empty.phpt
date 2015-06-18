<?php

/**
 * Test: Latte\Compiler and htmlNode.
 */

use Latte\IMacro;
use Latte\MacroNode;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockMacro implements IMacro
{
	public $empty;

	function initialize() {}

	function finalize() {}

	function nodeOpened(MacroNode $node)
	{
		Assert::same($this->empty, $node->htmlNode->isEmpty);
	}

	function nodeClosed(MacroNode $node) {}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$macro = new MockMacro;
$latte->addMacro('foo', $macro);

$macro->empty = TRUE;
Assert::match('%A%<input>%A%', $latte->compile('<input n:foo>'));
Assert::match('%A%<input>%A%', $latte->compile('<input n:foo />'));
Assert::match('%A%<textarea></textarea>%A%', $latte->compile('<textarea n:foo />'));
Assert::match("%A%<textarea></textarea>\n %A%", $latte->compile("<textarea n:foo />\n "));

$macro->empty = FALSE;
Assert::match('%A%<textarea></textarea>%A%', $latte->compile('<textarea n:foo></textarea>'));


$latte->setContentType($latte::CONTENT_XHTML);

$macro->empty = TRUE;
Assert::match('%A%<input />%A%', $latte->compile('<input n:foo>'));
Assert::match('%A%<input />%A%', $latte->compile('<input n:foo />'));
Assert::match('%A%<textarea></textarea>%A%', $latte->compile('<textarea n:foo />'));
Assert::match("%A%<textarea></textarea>\n %A%", $latte->compile("<textarea n:foo />\n "));

$macro->empty = FALSE;
Assert::match('%A%<textarea></textarea>%A%', $latte->compile('<textarea n:foo></textarea>'));


$latte->setContentType($latte::CONTENT_XML);

$macro->empty = TRUE;
Assert::match('%A%<input />%A%', $latte->compile('<input n:foo />'));
Assert::match('%A%<textarea />%A%', $latte->compile('<textarea n:foo />'));
Assert::match("%A%<textarea />\n %A%", $latte->compile("<textarea n:foo />\n "));

$macro->empty = FALSE;
Assert::match('%A%<textarea></textarea>%A%', $latte->compile('<textarea n:foo></textarea>'));

Assert::exception(function () use ($latte) {
	$latte->compile('<input n:foo>');
}, 'Latte\CompileException', 'Missing </input> for n:foo');
