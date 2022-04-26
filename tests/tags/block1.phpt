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
		<div id="main">
			side
		</div> <!-- /main -->


	side
		true
	true
		<div id="main">
			side
		</div> <!-- /main -->
	EOD
, $latte->renderToString(
	<<<'EOD'
			{block main}
			<div id="main">
				{block sidebar}side{/block}
			</div> <!-- /main -->
			{/block}

			{include sidebar}

			{block true}true{/block}
			{include true}

		{include main}
		EOD,
));
