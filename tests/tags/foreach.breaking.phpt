<?php

/**
 * Test: {foreach} + {continueIf}, {breakIf}, {skipIf}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{breakIf true}'),
	Latte\CompileException::class,
	'Tag {breakIf} is unexpected here (on line 1 at column 1)',
);

Assert::exception(
	fn() => $latte->compile('{breakIf}'),
	Latte\CompileException::class,
	'Missing arguments in {breakIf} (on line 1 at column 1)',
);

Assert::noError(fn() => $latte->compile('{for ;;}{if true}{breakIf true}{/if}{/for}'));


$template = <<<'EOD'

	{foreach [0, 1, 2, 3] as $item}
		{continueIf $item % 2}
		{$iterator->counter}. item
	{/foreach}

	---

	{foreach [0, 1, 2, 3] as $item}
		{skipIf $item % 2}
		{$iterator->counter}. item
	{/foreach}

	---

	{foreach [0, 1, 2, 3] as $item}
		{breakIf $item % 2}
		{$iterator->counter}. item
	{/foreach}


	EOD;

Assert::matchFile(
	__DIR__ . '/expected/foreach.breaking.php',
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

			1. item
			3. item

		---

			1. item
			2. item

		---

			1. item

		XX,
	$latte->renderToString($template),
);


$template = <<<'EOD'

	<ul title="foreach break">
		<li n:foreach="[0, 1, 2, 3] as $i">{$i}{breakIf true}</li>
	</ul>

	<ul title="foreach continue">
		<li n:foreach="[0, 1, 2, 3] as $i">{$i}{continueIf true}</li>
	</ul>

	<ul title="foreach skip">
		<li n:foreach="[0, 1, 2, 3] as $i">{$i}{skipIf true}</li>
	</ul>


	<ul title="inner foreach break">
		<li n:inner-foreach="[0, 1, 2, 3] as $i">{$i}{breakIf true}</li>
	</ul>

	<ul title="inner foreach continue">
		<li n:inner-foreach="[0, 1, 2, 3] as $i">{$i}{continueIf true}</li>
	</ul>

	<ul title="inner foreach skip">
		<li n:inner-foreach="[0, 1, 2, 3] as $i">{$i}{skipIf true}</li>
	</ul>

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/foreach.breaking.attr.php',
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

		<ul title="foreach break">
			<li>0</li>
		</ul>

		<ul title="foreach continue">
			<li>0</li>
			<li>1</li>
			<li>2</li>
			<li>3</li>
		</ul>

		<ul title="foreach skip">
			<li>0</li>
			<li>1</li>
			<li>2</li>
			<li>3</li>
		</ul>


		<ul title="inner foreach break">
			<li>0</li>
		</ul>

		<ul title="inner foreach continue">
			<li>0123</li>
		</ul>

		<ul title="inner foreach skip">
			<li>0123</li>
		</ul>

		XX,
	$latte->renderToString($template),
);
