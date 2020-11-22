<?php

/**
 * Test: Latte\Compiler and htmlNode.
 */

declare(strict_types=1);

use Latte\Compiler\Compiler;
use Latte\Compiler\Macro;
use Latte\Compiler\MacroNode;
use Latte\Compiler\Parser;
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
		Assert::same(2, $node->htmlNode->startLine);
	}


	public function nodeClosed(MacroNode $node)
	{
		Assert::same(6, $node->htmlNode->endLine);
	}
}


$parser = new Parser;
$compiler = new Compiler;
$compiler->addMacro('foo', new MockMacro);
$compiler->compile($parser->parse('
	<div
	n:foo
	>

	</div
	>'), 'Template');
