<?php

/**
 * Test: fix for #58.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'x',
	$latte->renderToString('{contentType application/xml}{if TRUE}x{/if}')
);
