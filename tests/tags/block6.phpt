<?php

/**
 * Test: Latte\Engine and blocks.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


// order of block & include
Assert::match(
	<<<'XX'



			X
		XX,
	$latte->renderToString(
		<<<'XX'

			{define a}
				{var $x = "X"}
				{include #b}
			{/define}

			{define b}
				{$x}
			{/define}

			{include a}

			XX,
	),
);
