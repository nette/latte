<?php

/**
 * Test: Latte\Compiler and htmlNode.
 */

use Latte\IMacro,
	Latte\MacroNode,
	Latte\Parser,
	Latte\Compiler,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockMacro implements IMacro
{
	function initialize() {}

	function finalize() {}

	function nodeOpened(MacroNode $node)
	{
		$res = array(
			1 => array('a' => '', 'b' => '123', 'c' => 'abc', 'd' => 'text', 'e' => 'xxx', 'f' => TRUE),
			2 => array('a' => '', 'b' => '456', 'c' => 'abc', 'd' => 'text', 'e' => 'xxx', 'f' => TRUE, 'g' => TRUE),
			3 => array('a' => '', 'b' => '456', 'c' => 'abc', 'd' => 'text', 'e' => 'xxx', 'f' => TRUE, 'g' => TRUE),
		);
		Assert::same($res[$node->args], $node->htmlNode->attrs);
	}

	function nodeClosed(MacroNode $node) {}
}


$parser = new Parser;
$compiler = new Compiler;
$compiler->addMacro('foo', new MockMacro);
$compiler->compile($parser->parse('<div a b=123 c = abc d="text" e=\'xxx\' f={foo 1/} b="456" g="a{foo 2/}b"> {foo 3/}'));
