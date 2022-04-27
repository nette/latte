<?php

/**
 * Test: local blocks
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


Assert::exception(
	fn() => $latte->renderToString('{block local, a}'),
	Latte\CompileException::class,
	"Unexpected arguments 'a' in {block} (at column 1)",
);


Assert::same(
	'local		local',
	trim($latte->renderToString(
		<<<'XX'

					{include abc}
					{block local abc}local{/block}

			XX,
	)),
);

Assert::same(
	'5 4 3 2 1 0',
	trim($latte->renderToString(
		<<<'XX'

					{var $i = 5}
					{block local abc}{$i} {if $i}{include this i: $i - 1}{/if} {/block}

			XX,
	)),
);


Assert::same(
	'5 4 3 2 1 0',
	trim($latte->renderToString(
		<<<'XX'

					{include abc i: 5}
					{define local abc}{$i} {if $i}{include this i: $i - 1}{/if} {/define}

			XX,
	)),
);


Assert::same(
	'5',
	trim($latte->renderToString('{var $i = 5} {block local abc}{$i}{/block}')),
);


Assert::same(
	'5',
	trim($latte->renderToString(
		<<<'XX'

					{var $i = 5}
					{include abc}
					{define local abc}{$i}{/define}

			XX,
	)),
);


Assert::same(
	'5',
	trim($latte->renderToString(
		<<<'XX'

					{define local abc}{$i}{/define}
					{var $i = 5}
					{include abc}

			XX,
	)),
);


Assert::exception(
	fn() => $latte->renderToString('{block local a}local{/block} {block a}classic{/block}'),
	Latte\CompileException::class,
	"Cannot redeclare block 'a' (at column 30)",
);


Assert::exception(
	fn() => $latte->renderToString('{block local abc}{include parent}{/block}'),
	Latte\RuntimeException::class,
	"Cannot include undefined parent block 'abc'.",
);


Assert::exception(
	fn() => $latte->renderToString('{block a}local{/block} {block local a}classic{/block}'),
	Latte\CompileException::class,
	"Cannot redeclare block 'a' (at column 24)",
);


$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'

				{import "inc"}
				{include a}

		XX,
	'inc' => '{block local a}{/block}',
]));

Assert::exception(
	fn() => $latte->renderToString('main'),
	Latte\RuntimeException::class,
	"Cannot include undefined block 'a'.",
);


$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'

				{import "inc"}
				{block local a}{include parent}{/block}

		XX,
	'inc' => '{block local a}{/block}',
]));

Assert::exception(
	fn() => $latte->renderToString('main'),
	Latte\RuntimeException::class,
	"Cannot include undefined parent block 'a'.",
);


$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'

				{extends "inc"}
				{block local a}{/block}

		XX,
	'inc' => '{include a}',
]));

Assert::exception(
	fn() => $latte->renderToString('main'),
	Latte\RuntimeException::class,
	"Cannot include undefined block 'a'.",
);
