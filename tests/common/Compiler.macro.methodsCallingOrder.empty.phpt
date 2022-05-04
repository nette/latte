<?php

/**
 * Test: Latte\Compiler and macro methods calling order.
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
			isset($node->htmlNode) ? $node->htmlNode->name : null,
			$node->closing,
			$node->prefix,
			$node->content,
		];
		$node->empty = true;
	}


	public function nodeClosed(MacroNode $node)
	{
		$this->calls[] = [
			__FUNCTION__,
			isset($node->htmlNode) ? $node->htmlNode->name : null,
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
	['nodeOpened', null, false, null, null],

	// <div1>{foo}</div1>
	['nodeOpened', 'div1', false, null, null],

	// <div2 n:foo>Text</div2>
	['nodeOpened', 'div2', false, 'none', null],

	'finalize',
], $macro->calls);
