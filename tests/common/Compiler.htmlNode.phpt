<?php

/**
 * Test: Latte\Compiler and htmlNode.
 */

declare(strict_types=1);

use Latte\Compiler;
use Latte\Macro;
use Latte\MacroNode;
use Latte\Parser;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockMacro implements Macro
{
	public function initialize()
	{
	}


	public function finalize()
	{
	}


	public function nodeOpened(MacroNode $node)
	{
		$res = [
			1 => ['a' => '', 'b' => '123', 'c' => 'abc', 'd' => 'text', 'e' => 'xxx', 'f' => true],
			2 => ['a' => '', 'b' => '456', 'c' => 'abc', 'd' => 'text', 'e' => 'xxx', 'f' => true, 'g' => true],
			3 => ['a' => '', 'b' => '456', 'c' => 'abc', 'd' => 'text', 'e' => 'xxx', 'f' => true, 'g' => true],
			4 => ['href' => true],
		];
		Assert::same($res[$node->args], $node->htmlNode->attrs);
	}


	public function nodeClosed(MacroNode $node)
	{
	}
}


$parser = new Parser;
$compiler = new Compiler;
$compiler->addMacro('foo', new MockMacro);
$compiler->compile($parser->parse('<div a b=123 c = abc d="text" e=\'xxx\' f={foo 1/} b="456" g="a{foo 2/}b"> {foo 3/}'), 'Template');
$compiler->compile($parser->parse('<a href={foo 4/}>'), 'Template');
