<?php

/**
 * Test: {foreach}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
{foreach [a, b] as $item}
	{foreach [c, d] as $item}
		{$iterator->parent->current()}
	{/foreach}
{/foreach}
',
]));


Assert::match(
	'
		a
		a
		b
		b',
	$latte->renderToString('main'),
);



$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => '
{foreach [a, b] as $item}
	{include included.latte}
{/foreach}
',
	'included.latte' => '
{foreach [c, d] as $item}
	{$iterator->parent ? "has parent"}
{/foreach}
',
]));


Assert::match(
	'',
	$latte->renderToString('main'),
);
