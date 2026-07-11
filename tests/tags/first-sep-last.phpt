<?php declare(strict_types=1);

/**
 * Test: {first}, {last}, {sep}.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::error(
	fn() => $latte->compile('{first}x{/first}'),
	E_USER_DEPRECATED,
	'Tag {first} outside {foreach} is deprecated (on line 1 at column 1)',
);

Assert::error(
	fn() => $latte->compile('{foreach [] as $x}{/foreach} {sep}x{/sep}'),
	E_USER_DEPRECATED,
	'Tag {sep} outside {foreach} is deprecated (on line 1 at column 30)',
);

// {first} in attribute of the element carrying n:foreach is fine
Assert::noError(fn() => $latte->compile('<p n:foreach="[] as $x" class="{first}a{/first}"></p>'));

// {last} inside {block} inside {foreach} is fine
Assert::noError(fn() => $latte->compile('{foreach [] as $x}{block a}{last}x{/last}{/block}{/foreach}'));

$template = <<<'EOD'

	{foreach $people as $person}
		{first}({/first} {$person}{sep}, {/sep} {last}){/last}
	{/foreach}


	{foreach $people as $person}
		{first}({else}[{/first} {$person}{sep}, {else};{/sep} {last}){else}]{/last}
	{/foreach}


	{foreach $people as $person}
		{first 2}({/first} {$person}{sep 2}, {/sep} {last 2}){/last}
	{/foreach}


	{foreach $people as $person}
		{first 1}({/first} {$person}{sep 1}, {/sep} {last 1}){/last}
	{/foreach}


	{foreach $people as $person}
		<span n:first=0>(</span> {$person}<span n:sep>, </span> <span n:last>)</span>
	{/foreach}


	<p n:foreach="$people as $person" class="{first}$person{/first}"></p>

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/first-sep-last.php',
	$latte->compile($template),
);
Assert::matchFile(
	__DIR__ . '/expected/first-sep-last.html',
	$latte->renderToString($template, ['people' => ['John', 'Mary', 'Paul']]),
);
