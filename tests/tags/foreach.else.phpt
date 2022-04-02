<?php

/**
 * Test: foreach + else
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


Assert::matchFile(
	__DIR__ . '/expected/foreach.else.phtml',
	$latte->compile(
		<<<'XX'

			{foreach [a] as $item}
				item
				{else}
				empty
			{/foreach}

			XX,
	),
);


Assert::match(
	<<<'XX'

			Empty

		XX,
	$latte->renderToString(
		<<<'XX'

			{foreach [] as $item}
				Items
			{else}
				Empty
			{/foreach}

			XX,
	),
);


Assert::match(
	<<<'XX'

			Items

		XX,
	$latte->renderToString(
		<<<'XX'

			{foreach [1] as $item}
				Items
			{else}
				Empty
			{/foreach}

			XX,
	),
);


Assert::match(
	<<<'XX'

			Empty

		XX,
	$latte->renderToString(
		<<<'XX'

			{foreach [1] as $item}
				{skipIf true}
				Items
			{else}
				Empty
			{/foreach}

			XX,
	),
);


Assert::match(
	<<<'XX'

				Empty Inner

		XX,
	$latte->renderToString(
		<<<'XX'

			{foreach [1] as $item}
				{foreach [] as $item}
					Items
				{else}
					Empty Inner
				{/foreach}
			{else}
				Empty Outer
			{/foreach}

			XX,
	),
);


Assert::match(
	<<<'XX'

			Empty Outer

		XX,
	$latte->renderToString(
		<<<'XX'

			{foreach [] as $item}
				{foreach [1] as $item}
					Items
				{else}
					Empty Inner
				{/foreach}
			{else}
				Empty Outer
			{/foreach}

			XX,
	),
);
