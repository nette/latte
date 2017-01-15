<?php

/**
 * Test: Latte\Compiler and htmlNode.
 */

declare(strict_types=1);

use Latte\IMacro;
use Latte\MacroNode;
use Latte\Parser;
use Latte\Compiler;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MockMacro implements IMacro
{
	function initialize() {}

	function finalize() {}

	function nodeOpened(MacroNode $node)
	{
		Assert::same(2, $node->htmlNode->startLine);
	}

	function nodeClosed(MacroNode $node)
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
