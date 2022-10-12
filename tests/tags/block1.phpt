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
	EOD, $latte->renderToString(<<<'EOD'
		{block main}
		<div id="main">
			{block sidebar}side{/block}
		</div> <!-- /main -->
		{/block}

		{include sidebar}

		{block true}true{/block}
		{include true}

	{include main}
	EOD));


$node = $latte->parse('{block main |trim}...{/block}');

Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Block:
				String:
					value: main
				Modifier:
					Filter:
						Identifier:
							name: trim
				Fragment:
					Text:
						content: '...'
	XX, exportAST($node));
