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
			$node->isEmpty,
		];
		$node->isEmpty = FALSE;
	}

	public function nodeClosed(MacroNode $node)
	{
		$this->calls[] = [
			__FUNCTION__,
			isset($node->htmlNode) ? $node->htmlNode->name : NULL,
			$node->closing,
			$node->prefix,
			preg_replace('#n:\w+#', 'n:#', $node->content),
			$node->isEmpty,
		];
	}
}

$latte = '
	{foo}Text{/foo}
	{foo}{/foo}
	{foo/}
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

	// {foo}Text{/foo}
	['nodeOpened', NULL, FALSE, NULL, NULL, FALSE],
	['nodeClosed', NULL, TRUE, NULL, 'Text', FALSE],

	// {foo}{/foo}
	['nodeOpened', NULL, FALSE, NULL, NULL, FALSE],
	['nodeClosed', NULL, TRUE, NULL, '', FALSE],

	// {foo/}
	['nodeOpened', NULL, FALSE, NULL, NULL, FALSE],
	['nodeClosed', NULL, TRUE, NULL, '', FALSE],

	// <div1>{foo}Text{/foo}</div1>
	['nodeOpened', 'div1', FALSE, NULL, NULL, FALSE],
	['nodeClosed', 'div1', TRUE, NULL, 'Text', FALSE],

	// <div2 n:foo>Text</div2>
	['nodeOpened', 'div2', FALSE, 'none', NULL, FALSE],
	['nodeClosed', 'div2', TRUE, 'none', "\t<div2 n:#>Text</div2>\n", FALSE],

	// <div3 n:inner-foo>Text</div3>
	['nodeOpened', 'div3', FALSE, 'inner', NULL, FALSE],
	['nodeClosed', 'div3', TRUE, 'inner', 'Text', FALSE],

	// <div4 n:tag-foo>Text</div4>
	['nodeOpened', 'div4', FALSE, 'tag', NULL, FALSE],
	['nodeClosed', 'div4', TRUE, 'tag', '	<div4 n:#>', FALSE],

	// <div4 n:tag-foo>Text</div4>
	['nodeOpened', 'div4', FALSE, 'tag', NULL, FALSE],
	['nodeClosed', 'div4', TRUE, 'tag', "</div4>\n", FALSE],

	'finalize',
], $macro->calls);
