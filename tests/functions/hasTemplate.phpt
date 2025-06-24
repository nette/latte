<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => '{=var_export(hasTemplate(inc), true)}',
	'main2' => '{=var_export(hasTemplate(undefined), true)}',
	'inc' => '',
]));

Assert::match(
	'true',
	$latte->renderToString('main1'),
);

Assert::match(
	'false',
	$latte->renderToString('main2'),
);
