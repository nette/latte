<?php declare(strict_types=1);

/**
 * Test: {foreach}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

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

	{foreach [['a', null, null]] as list($a, , [$b, list($c)])}{/foreach}

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
