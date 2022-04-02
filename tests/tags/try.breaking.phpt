<?php

/**
 * Test: {try} ... {/try}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


// restoring output buffer after breakIf/continueIf
Assert::match(
	<<<'X'
				inner 1
				inner 2
				inner 3
		X
,
	$latte->renderToString(
		<<<'XX'
			{foreach [1,2,3] as $n}
				{try}
					inner {$n}
					{continueIf true}
				{/try}
			{/foreach}
			XX,
	),
);


Assert::match(
	<<<'X'
				inner 1
				inner 2
				inner 3
		X
,
	$latte->renderToString(
		<<<'XX'
			{foreach [1,2,3] as $n}
				{try}
					inner {$n}
					{continueIf true}
					{else}
					else
				{/try}
			{/foreach}
			XX,
	),
);
