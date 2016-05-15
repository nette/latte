<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => 'before {sandbox inc1.latte} after',
	'main2' => 'before {sandbox inc1.latte, var => 1} after',

	'inc1.latte' => '<b>included {$var}</b>',
]));


Assert::error(function () use ($latte) {
	$latte->renderToString('main1');
}, E_NOTICE, 'Undefined variable: var');

Assert::error(function () use ($latte) {
	$latte->renderToString('main1', ['var' => 123]);
}, E_NOTICE, 'Undefined variable: var');

Assert::match(
	'before <b>included 1</b> after',
	$latte->renderToString('main2')
);
