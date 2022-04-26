<?php

/**
 * Test: {include file}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => 'before {include inc.latte var => 1} after',
	'main2' => 'before {include inc.latte var => 1|striptags} after',
	'main3' => 'before {include inc.latte var => 1|striptags|noescape} after',
	'main4' => 'before {include inc.latte var: named} after',
	'main5' => 'before {include file inc2 var => 1} after',
	'main6' => 'before {include file "inc" . 2, var => 1} after',
	'main7' => '{include inc.latte $a = 1, var => 2}',

	'inc.latte' => '<b>included {$var}</b>',
	'inc2' => '<b>included {$var}</b>',
]));

Assert::match(
	'before <b>included 1</b> after',
	$latte->renderToString('main1'),
);

Assert::match(
	'before included 1 after',
	$latte->renderToString('main2'),
);

Assert::match(
	'before included 1 after',
	$latte->renderToString('main3'),
);

Assert::match(
	'before <b>included named</b> after',
	$latte->renderToString('main4'),
);

Assert::match(
	'before <b>included 1</b> after',
	$latte->renderToString('main5'),
);

Assert::match(
	'before <b>included 1</b> after',
	$latte->renderToString('main6'),
);

Assert::error(function () use ($latte) {
	$latte->renderToString('main7');
}, E_USER_NOTICE, 'The assignment in the {include inc.latte $a = ...} looks like an error.');
