<?php

/**
 * Test: Latte\Compiler and macro methods calling order.
 */

use Latte\IMacro;
use Latte\MacroNode;
use Latte\Parser;
use Latte\Compiler;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockMacro implements IMacro
{
	public $calls = [];

	public function initialize()
	{
		$this->calls[] = __FUNCTION__;
	}

	public function finalize()
	{
		$this->calls[] = __FUNCTION__;
	}

	public function nodeOpened(MacroNode $node)
	{
		$this->calls[] = [
			__FUNCTION__,
			isset($node->htmlNode) ? $node->htmlNode->name : NULL,
			$node->closing,
			$node->prefix,
			$node->content,
		];
		$node->empty = TRUE;
	}

	public function nodeClosed(MacroNode $node)
	{
		$this->calls[] = [
			__FUNCTION__,
			isset($node->htmlNode) ? $node->htmlNode->name : NULL,
			$node->closing,
			$node->prefix,
			$node->content,
		];
	}
}

$latte = '
	{foo}
	<div1>{foo}</div1>
	<div2 n:foo>Text</div2>
';

$macro = new MockMacro;
$parser = new Parser;
$compiler = new Compiler;
$compiler->addMacro('foo', $macro);
$compiler->compile($parser->parse($latte), 'Template');

Assert::same([
	'initialize',

	// {foo}
	['nodeOpened', NULL, FALSE, NULL, NULL],

	// <div1>{foo}</div1>
	['nodeOpened', 'div1', FALSE, NULL, NULL],

	// <div2 n:foo>Text</div2>
	['nodeOpened', 'div2', FALSE, 'none', NULL],

	'finalize',
], $macro->calls);
