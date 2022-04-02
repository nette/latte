<?php

/**
 * Test: {foreach}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'
		{foreach [a, b] as $item}
			{foreach [c, d] as $item}
				{$iterator->parent->current()}
			{/foreach}
		{/foreach}

		XX,
]));


Assert::match(
	<<<'XX'
				a
				a
				b
				b
		XX,
	$latte->renderToString('main'),
);



$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'
		{foreach [a, b] as $item}
			{include included.latte}
		{/foreach}

		XX,
	'included.latte' => <<<'XX'
		{foreach [c, d] as $item}
			{$iterator->parent ? "has parent"}
		{/foreach}

		XX,
]));


Assert::match(
	'',
	$latte->renderToString('main'),
);
