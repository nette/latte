<?php

/**
 * Test: Latte\Engine: {contentType application/xml}
 */

use Latte\Runtime\Html;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


restore_error_handler();


$latte = new Latte\Engine;
$latte->setContentType($latte::CONTENT_XML);

$params['hello'] = '<i>Hello</i>';
$params['id'] = ':/item';
$params['people'] = array('John', 'Mary', 'Paul', ']]> <!--');
$params['comment'] = 'test -- comment';
$params['el'] = new Html("<div title='1/2\"'></div>");

Assert::matchFile(
	__DIR__ . '/expected/contentType.xml.phtml',
	$latte->compile(__DIR__ . '/templates/contentType.xml.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/contentType.xml.html',
	$latte->renderToString(
		__DIR__ . '/templates/contentType.xml.latte',
		$params
	)
);
