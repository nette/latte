<?php

/**
 * Test: dynamic snippets test.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
	{snippet outer1}
	{foreach array(1,2,3) as $id}
		<div n:snippet="inner-{$id}">
				#{$id}
		</div>
	{/foreach}
	{/snippet}


	{snippet outer2}
	{foreach array(1,2,3) as $id}
		<div n:inner-snippet="inner-{$id}">
				#{$id}
		</div>
	{/foreach}
	{/snippet}
EOD;

Assert::matchFile(
	__DIR__ . '/expected/snippet.dynamic.alt.phtml',
	$latte->compile($template)
);
