<?php

/**
 * Test: Latte\Engine: general XHTML test.
 */

use Latte\Runtime\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setTempDirectory(TEMP_DIR);
$latte->setContentType($latte::CONTENT_XHTML);
$latte->addFilter('translate', 'strrev');
$latte->addFilter('join', 'implode');

$params['hello'] = '<i>Hello</i>';
$params['xss'] = 'some&<>"\'/chars';
$params['mxss'] = '`mxss';
$params['people'] = ['John', 'Mary', 'Paul', ']]> <!--'];
$params['menu'] = ['about', ['product1', 'product2'], 'contact'];
$params['el'] = new Html("<div title='1/2\"'></div>");

Assert::matchFile(
	__DIR__ . '/expected/macros.general.xhtml.phtml',
	$latte->compile(__DIR__ . '/templates/general.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/macros.general.xhtml.html',
	$latte->renderToString(
		__DIR__ . '/templates/general.latte',
		$params
	)
);
