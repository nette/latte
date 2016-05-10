<?php

/**
 * Test: Latte\Engine: unquoted attributes.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
<span title={$x}></span>

<span title={$x} {$x}></span>

<span {='title'}={$x}></span>

EOD;

Assert::matchFile(
	__DIR__ . '/expected/compiler.unquoted.phtml',
	$latte->compile($template)
);
Assert::matchFile(
	__DIR__ . '/expected/compiler.unquoted.html',
	$latte->renderToString($template, ['x' => '\' & "'])
);
