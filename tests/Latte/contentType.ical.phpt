<?php

/**
 * Test: Latte\Engine: iCal template
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->addFilter('escape', 'Latte\Runtime\Filters::escapeICal');

Assert::matchFile(
	__DIR__ . '/expected/contentType.ical.phtml',
	$latte->compile(__DIR__ . '/templates/ical.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/contentType.ical.html',
	$latte->renderToString(
		__DIR__ . '/templates/ical.latte'
	)
);
