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
		$this->calls[] = [__FUNCTION__, isset($node->htmlNode) ? $node->htmlNode->name : NULL, $node->closing, $node->prefix];
	}

	public function nodeClosed(MacroNode $node)
	{
		$this->calls[] = [__FUNCTION__, isset($node->htmlNode) ? $node->htmlNode->name : NULL, $node->closing, $node->prefix];
	}
}

$latte = '
	{foo}Text{/foo}
	<div1>{foo}Text{/foo}</div1>
	<div2 n:foo>Text</div2>
	<div3 n:inner-foo>Text</div3>
	<div4 n:tag-foo>Text</div4>
';

$macro = new MockMacro;
$parser = new Parser;
$compiler = new Compiler;
$compiler->addMacro('foo', $macro);
$compiler->compile($parser->parse($latte), 'Template');

Assert::same([
	'initialize',

	['nodeOpened', NULL, FALSE, NULL],
	['nodeClosed', NULL, TRUE, NULL],

	['nodeOpened', 'div1', FALSE, NULL],
	['nodeClosed', 'div1', TRUE, NULL],

	['nodeOpened', 'div2', FALSE, 'none'],
	['nodeClosed', 'div2', TRUE, 'none'],

	['nodeOpened', 'div3', FALSE, 'inner'],
	['nodeClosed', 'div3', TRUE, 'inner'],

	['nodeOpened', 'div4', FALSE, 'tag'],
	['nodeClosed', 'div4', TRUE, 'tag'],
	['nodeOpened', 'div4', FALSE, 'tag'],
	['nodeClosed', 'div4', TRUE, 'tag'],

	'finalize',
], $macro->calls);
