<?php

/**
 * Test: {syntax ...}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

// double
$template = <<<'EOD'
	{syntax double}
		{if $var} {$var} {/if}
		{{if $var}} {{$var}} {{/if}}
		{* comment single *}
		{{* comment double *}}
	{/syntax}

	{$after}
	EOD;

Assert::match(
	<<<'XX'
			{if $var} {$var} {/if}
			 var
			{* comment single *}

		after
		XX,
	$latte->renderToString($template, ['var' => 'var', 'after' => 'after']),
);


// double n:attribute
$template = <<<'EOD'
	<ul n:syntax="double">
		{if $var} {$var} {/if}
		{{if $var}} {{$var}} {{/if}}
		{/syntax}
		{* comment single *}
		{{* comment double *}}
	</ul>

	{$after}
	EOD;

Assert::match(
	<<<'XX'
		<ul>
			{if $var} {$var} {/if}
			 var
			{/syntax}
			{* comment single *}
		</ul>

		after
		XX,
	$latte->renderToString($template, ['var' => 'var', 'after' => 'after']),
);


// off
$template = <<<'EOD'
	{syntax off}
		{if $var} {$var} {/if}
		{ {/ {/syntax
		{* comment single *}
	{/syntax}

	{$after}
	EOD;

Assert::match(
	<<<'XX'
			{if $var} {$var} {/if}
			{ {/ {/syntax
			{* comment single *}

		after
		XX,
	$latte->renderToString($template, ['var' => 'var', 'after' => 'after']),
);


// off n:attribute
$template = <<<'EOD'
	<ul n:syntax="off">
		{if $var} {$var} {/if}
		{/syntax}
		{* comment single *}
	</ul> {$after}
	EOD;

Assert::match(
	<<<'XX'
		<ul>
			{if $var} {$var} {/if}
			{/syntax}
			{* comment single *}
		</ul> after
		XX,
	$latte->renderToString($template, ['var' => 'var', 'after' => 'after']),
);


// nested
$template = <<<'EOD'
	<ul n:syntax="double">
		{if $var} {$var} {/if}
		{{if $var}} {{$var}} {{/if}}
		<div n:syntax=off>
			{$inner}
		</div>
		{$after}
		{{$after}}
	</ul>

	{$after}
	EOD;

Assert::match(
	<<<'XX'
		<ul>
			{if $var} {$var} {/if}
			 var
			<div>
				{$inner}
			</div>
			{$after}
			after
		</ul>

		after
		XX,
	$latte->renderToString($template, ['var' => 'var', 'after' => 'after']),
);
