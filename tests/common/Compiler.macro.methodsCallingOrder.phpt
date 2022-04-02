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
			$node->empty,
		];
		$node->empty = false;
	}


	public function nodeClosed(MacroNode $node)
	{
		$this->calls[] = [
			__FUNCTION__,
			isset($node->htmlNode) ? $node->htmlNode->name : null,
			$node->closing,
			$node->prefix,
			preg_replace('#n:\w+#', 'n:#', $node->content),
			$node->empty,
		];
	}
}

$latte = <<<'XX'

		{foo}Text{/foo}
		{foo}{/foo}
		{foo/}
		<div1>{foo}Text{/foo}</div1>
		<div2 n:foo>Text</div2>
		<div3 n:inner-foo>Text</div3>
		<div4 n:tag-foo>Text</div4>

	XX;

$macro = new MockMacro;
$parser = new Parser;
$compiler = new Compiler;
$compiler->addMacro('foo', $macro);
$compiler->compile($parser->parse($latte), 'Template');

Assert::same([
	'initialize',

	// {foo}Text{/foo}
	['nodeOpened', null, false, null, null, false],
	['nodeClosed', null, true, null, 'Text', false],

	// {foo}{/foo}
	['nodeOpened', null, false, null, null, false],
	['nodeClosed', null, true, null, '', false],

	// {foo/}
	['nodeOpened', null, false, null, null, false],
	['nodeClosed', null, true, null, '', false],

	// <div1>{foo}Text{/foo}</div1>
	['nodeOpened', 'div1', false, null, null, false],
	['nodeClosed', 'div1', true, null, 'Text', false],

	// <div2 n:foo>Text</div2>
	['nodeOpened', 'div2', false, 'none', null, false],
	['nodeClosed', 'div2', true, 'none', "\t<div2 n:#><n:#>Text<n:#></div2>\n", false],

	// <div3 n:inner-foo>Text</div3>
	['nodeOpened', 'div3', false, 'inner', null, false],
	['nodeClosed', 'div3', true, 'inner', 'Text', false],

	// <div4 n:tag-foo>Text</div4>
	['nodeOpened', 'div4', false, 'tag', null, false],
	['nodeClosed', 'div4', true, 'tag', '	<div4 n:#>', false],

	// <div4 n:tag-foo>Text</div4>
	['nodeOpened', 'div4', false, 'tag', null, false],
	['nodeClosed', 'div4', true, 'tag', "</div4>\n", false],

	'finalize',
], $macro->calls);
