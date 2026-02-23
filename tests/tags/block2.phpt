<?php declare(strict_types=1);

/**
 * Test: Latte\Engine and blocks.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::match(<<<'EOD'
	<head>
	</head>
	EOD, $latte->renderToString(
	<<<'EOD'
		<head>
			{block head}{/block}
		</head>
		EOD,
));
