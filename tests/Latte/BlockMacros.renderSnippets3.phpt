<?php

/**
 * Test: BlockMacros, renderSnippets and dynamic snippetArea with included template
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/mocks/SnippetBridge.php';

$bridge = new SnippetBridgeMock();
$bridge->invalid = ['foo' => TRUE, 'data' => TRUE];

$engine = new Latte\Engine();
$engine->addProvider('snippetBridge', $bridge);
$engine->render(__DIR__ . '/templates/snippetArea-include.latte');

Assert::same([
	'bar-1' => "1\n",
	'bar-2' => "2\n",
], $bridge->payload);
