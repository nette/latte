<?php

/**
 * Test: general snippets test.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'
		{snippet}

		{/snippet}



		{snippet outer}
		Outer
			{snippet inner}Inner{/snippet inner}
		/Outer
		{/snippet outer}



		@{if true} Hello World @{/if}

		{snippet title}Title 1{/snippet title}

		{snippet title2}Title 2{/snippet}
	EOD;

Assert::matchFile(
	__DIR__ . '/expected/snippet.phtml',
	$latte->compile($template),
);
