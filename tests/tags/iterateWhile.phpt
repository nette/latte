<?php

/**
 * Test: {iterateWhile}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{iterateWhile}'),
	Latte\CompileException::class,
	'Tag {iterateWhile} must be inside {foreach} ... {/foreach}.',
);


$template = <<<'EOD'

	{foreach [0, 1, 2, 3] as $item}
		pre {$item}
		{iterateWhile $item % 2}
			inner {$item}
		{/iterateWhile}
		post {$item}
	{/foreach}

	---

	{foreach [0, 1, 2, 3] as $item}
		pre {$item}
		{iterateWhile}
			inner {$item}
		{/iterateWhile $item % 2}
		post {$item}
	{/foreach}

	---

	{foreach [a => [0], b => [1], c => [2]] as $key => [$i]}
		pre {$key} {$i}
		{iterateWhile}
			inner {$key} {$i}
		{/iterateWhile true}
		post {$key} {$i}
	{/foreach}

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/iterateWhile.phtml',
	$latte->compile($template),
);

Assert::matchFile(
	__DIR__ . '/expected/iterateWhile.html',
	$latte->renderToString($template),
);
