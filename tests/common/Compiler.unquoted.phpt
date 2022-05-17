<?php

/**
 * Test: unquoted attributes.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
	<span title={$x} class={$x}></span>

	<span title={$x} {$x}></span>

	<span title={if true}{$x}{else}"item"{/if}></span>

	<span {='title'}={$x}></span>

	<span attr{$x}b=c{$x}d></span> {* not supported *}

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/Compiler.unquoted.attrs.phtml',
	$latte->compile($template),
);
Assert::matchFile(
	__DIR__ . '/expected/Compiler.unquoted.attrs.html',
	$latte->renderToString($template, ['x' => '\' & "']),
);
