<?php declare(strict_types=1);

/**
 * Test: variable tag names
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

$template = <<<'EOD'
	{var $tag = 'foo', $ns = 'ns'}

	<{$tag}>...</{$tag}>

	<{$ns}:{$tag}>...</{$ns}:{$tag}>

	<h{=1}>...</h{=1}>
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/Compiler.variable.tags.php',
	$latte->compile($template),
);
Assert::matchFile(
	__DIR__ . '/expected/Compiler.variable.tags.html',
	$latte->renderToString($template, ['x' => '\' & "']),
);
