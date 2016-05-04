<?php

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;

Assert::match(
	'This is definition #5',
	trim($latte->renderToString(__DIR__ . '/templates/defineblock.latte', ['var' => 5], 'test'))
);

Assert::match(
	'Variables 5, 6',
	trim($latte->renderToString(__DIR__ . '/templates/defineblock.latte', [5, 6], 'testargs'))
);

Assert::match(
	'Homepage | My websiteMy website',
	$latte->renderToString(__DIR__ . '/templates/inheritance.child1.latte', [], 'title')
);
