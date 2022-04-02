<?php

/**
 * Test: {define ...}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'XX'
	{define test $var1, $var2, $var3}
		Variables {$var1}, {$var2}, {$hello}
	{/define}

	a) {include test, 1}

	{define outer}
		{include test, hello}
	{/define}

	b) {include outer}

	{var $var1 = outer}
	c) {include test}

	d) {include test null}
	XX;

Assert::matchFile(
	__DIR__ . '/expected/define.args1.phtml',
	$latte->compile($template),
);
Assert::matchFile(
	__DIR__ . '/expected/define.args1.html',
	$latte->renderToString($template, ['hello' => 'world']),
);


//typehints
$template = <<<'XX'
	{define test $var1, ?stdClass $var2, \C\B|null $var3}
	{/define}

	{include test, 1}
	XX;

Assert::matchFile(
	__DIR__ . '/expected/define.typehints.phtml',
	$latte->compile($template),
);


// named arguments
$template = <<<'XX'
	named arguments

	{define test $var1, $var2, $var3}
		Variables {$var1}, {$var2}, {$var3}
	{/define}

	a) {include test, 1, var1 => 2}

	b) {include test, var2 => 1}

	c) {include test, hello => 1}

	d) {include test, var2 => 1, 2} // invalid
	XX;

Assert::matchFile(
	__DIR__ . '/expected/define.args2.phtml',
	$latte->compile($template),
);
Assert::matchFile(
	__DIR__ . '/expected/define.args2.html',
	$latte->renderToString($template, ['var3' => 'outer']),
);


// named arguments (order dependent)
$template = <<<'XX'
	named arguments order

	a) {include test, 1, var1 => 2}

	b) {include test, var2 => 1}

	c) {include test, hello => 1}

	{define test $var1, $var2, $var3}
		Variables {$var1}, {$var2}, {$var3}
	{/define}
	XX;

Assert::matchFile(
	__DIR__ . '/expected/define.args3.phtml',
	$latte->compile($template),
);


// named arguments (file dependent)
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'

		named arguments import

		{import import.latte}

		a) {include test, 1, var1 => 2}

		b) {include test, var2 => 1}

		c) {include test, hello => 1}
		XX,

	'import.latte' => <<<'XX'

		{define test $var1, $var2, $var3}
			Variables {$var1}, {$var2}, {$var3}
		{/define}
		XX,
]));

Assert::matchFile(
	__DIR__ . '/expected/define.args4.phtml',
	$latte->compile('main'),
);
Assert::matchFile(
	__DIR__ . '/expected/define.args4.html',
	$latte->renderToString('main', ['var3' => 'outer']),
);


// default values
$latte->setLoader(new Latte\Loaders\StringLoader);
$template = <<<'XX'
	default values

	{define test $var1 = 0, $var2 = [1, 2, 3], $var3 = 10}
		Variables {$var1}, {$var2|implode}, {$var3}
	{/define}

	a) {include test, 1}

	b) {include test, var1 => 1}
	XX;

Assert::matchFile(
	__DIR__ . '/expected/define.args5.phtml',
	$latte->compile($template),
);
Assert::matchFile(
	__DIR__ . '/expected/define.args5.html',
	$latte->renderToString($template),
);
