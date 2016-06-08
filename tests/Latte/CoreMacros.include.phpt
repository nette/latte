<?php

/**
 * Test: {include file}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => 'before {include inc.latte var => 1} after',
	'main2' => 'before {include inc.latte var => 1|striptags} after',
	'main3' => 'before {include inc.latte var => 1|striptags|noescape} after',

	'inc.latte' => '<b>included {$var}</b>',
]));

Assert::match(
	'before <b>included 1</b> after',
	$latte->renderToString('main1')
);

Assert::match(
	'before included 1 after',
	$latte->renderToString('main2')
);

Assert::match(
	'before included 1 after',
	$latte->renderToString('main3')
);
