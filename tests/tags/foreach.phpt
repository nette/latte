<?php

/**
 * Test: {foreach}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

	{foreach [a, b] as $item}
		item
	{/foreach}

	---

	{foreach [a, b] as $item}
		{$iterator->counter}
	{/foreach}
	{$iterator === null ? 'is null'}

	---

	{foreach [a, b] as list($a, , [$b, list($c)])}{/foreach}

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/foreach.php',
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

			item
			item

		---

			1
			2
		is null

		---
		XX,
	$latte->renderToString($template),
);
