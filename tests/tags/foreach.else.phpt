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
	$latte->compile('
{foreach [a] as $item}
	item
	{else}
	empty
{/foreach}
'),
);


Assert::match(
	'
	Empty
',
	$latte->renderToString('
{foreach [] as $item}
	Items
{else}
	Empty
{/foreach}
'),
);


Assert::match(
	'
	Items
',
	$latte->renderToString('
{foreach [1] as $item}
	Items
{else}
	Empty
{/foreach}
'),
);


Assert::match(
	'
	Empty
',
	$latte->renderToString('
{foreach [1] as $item}
	{skipIf true}
	Items
{else}
	Empty
{/foreach}
'),
);


Assert::match(
	'
		Empty Inner
',
	$latte->renderToString('
{foreach [1] as $item}
	{foreach [] as $item}
		Items
	{else}
		Empty Inner
	{/foreach}
{else}
	Empty Outer
{/foreach}
'),
);


Assert::match(
	'
	Empty Outer
',
	$latte->renderToString('
{foreach [] as $item}
	{foreach [1] as $item}
		Items
	{else}
		Empty Inner
	{/foreach}
{else}
	Empty Outer
{/foreach}
'),
);
