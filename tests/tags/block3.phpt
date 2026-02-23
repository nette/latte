<?php declare(strict_types=1);

/**
 * Test: Latte\Engine and blocks.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::match(<<<'EOD'
	<head>
		<script src="nette.js"></script>
		<link rel="alternate">
	</head>

		<link rel="alternate">
	EOD, $latte->renderToString(
	<<<'EOD'
		<head>
			<script src="nette.js"></script>
			{include meta}
		</head>

		{block meta}
			<link rel="alternate">
		{/block}
		EOD,
));
