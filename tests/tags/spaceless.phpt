<?php

/**
 * Test: {spaceless}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	<<<'XX'
			<hr>
			<div id="main space" class = 1> <p> Text </p> block </div> <!-- /main -->
			<hr>
		XX,
	$latte->renderToString(
		<<<'EOD'
				<hr>
				{spaceless}
				<div id="main   space"
				class = 1>
					<p>
						Text
					</p>
					{block sidebar}block{/block}
				</div> <!-- /main -->
				{/spaceless}
				<hr>
			EOD,
	),
);


Assert::match(
	<<<'XX'
			<hr>
			<div class = a> <p> Text </p> </div>
			<hr>
		XX,
	$latte->renderToString(
		<<<'EOD'
				<hr>
				<div n:spaceless   class =  a>
					<p>
						Text
					</p>
				</div>
				<hr>
			EOD,
	),
);


Assert::match(
	"<p> </p><pre>\n\n\n"
	. str_repeat('x', 10000)
	. "\n\n\n</pre> <p> </p>",
	$latte->renderToString(
		"{spaceless}<p>\n\n\n</p>"
		. "<pre>\n\n\n"
		. str_repeat('x', 5000)
		. '{if true}{/if}'
		. str_repeat('x', 5000)
		. "\n\n\n</pre> <p>\n\n\n</p>{/spaceless}",
	),
);
