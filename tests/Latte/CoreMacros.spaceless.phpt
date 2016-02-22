<?php

/**
 * Test: {spaceless}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
'	<hr>
<div id="main space" class = 1><p>Text</p>block</div><!-- /main -->
	<hr>',

	$latte->renderToString(<<<EOD
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
EOD
));


Assert::match(
'	<hr>
	<div class = a><p>Text</p></div>
	<hr>',

	$latte->renderToString(<<<EOD
	<hr>
	<div n:spaceless   class =  a>
		<p>
			Text
		</p>
	</div>
	<hr>
EOD
));
