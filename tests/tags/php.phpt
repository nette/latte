<?php

/**
 * Test: {php}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	<<<'XX'
		%A%$a = 'test' ? ([]) : null%A%
		XX,
	$latte->compile(
		<<<'XX'
			{php $a = test ? ([])}
			XX,
	),
);

Assert::match(
	<<<'XX'
		%A%$a = 'test' ? ([]) : null%A%
		XX,
	$latte->compile(
		<<<'XX'
			{php $a = test ? ([])}
			XX,
	),
);
