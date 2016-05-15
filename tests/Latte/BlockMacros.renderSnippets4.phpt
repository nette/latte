<?php

/**
 * Test: BlockMacros and renderSnippets with blocks included using includeblock
 */

use Nette\Bridges\ApplicationLatte\UIMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/mocks/SnippetBridge.php';

$bridge = new SnippetBridgeMock();
$bridge->invalid = TRUE;

$engine = new Latte\Engine();
$engine->addProvider('snippetBridge', $bridge);
$engine->render(__DIR__ . '/templates/snippets.includeblock.latte');

Assert::same([
	'test' => "bar",
], $bridge->payload);
