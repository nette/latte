<?php

/**
 * Test: {try} ... {/try}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


// restoring output buffer
Assert::match(
	'',
	$latte->renderToString(
		<<<'XX'
			{try}
				inner
				{if}
					if
					{rollback}
				{/if false}
			{/try}
			XX,
	),
);


// restoring $iterator
Assert::match(
	<<<'XX'
			a
			b

		is null
		XX,
	$latte->renderToString(
		<<<'XX'
			{foreach [a, b] as $a}
				{try}
				{foreach [1, 2] as $b}
						{rollback}
						{$iterator->counter}
				{/foreach}
				{/try}
				{$iterator->current()}
			{/foreach}

			{$iterator === null ? 'is null'}
			XX,
	),
);


// restoring blocks
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'X'
				{block test}test{/block}
				{try}
					{embed embed.latte}{/embed}
				{/try}
				{include test}
		X
	,
	'embed.latte' => '{rollback}',
]));

Assert::match(
	<<<'XX'
				test
				test
		XX,
	$latte->renderToString('main'),
);
