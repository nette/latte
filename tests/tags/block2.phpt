<?php

/**
 * Test: Latte\Engine and blocks.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

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
