<?php

/**
 * Test: BlockMacros, renderSnippets and template with layout
 */

use Nette\Bridges\ApplicationLatte\UIMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/mocks/SnippetBridge.php';

$bridge = new SnippetBridgeMock();
$bridge->invalid = ['foo' => TRUE];

$engine = new Latte\Engine();
$engine->addProvider('snippetBridge', $bridge);
$engine->render(__DIR__ . '/templates/snippets.extends.latte');

Assert::same([
	'foo' => "Hello",
], $bridge->payload);
