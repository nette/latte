<?php

/**
 * Test: variable tag names
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

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
