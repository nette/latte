<?php

/**
 * Test: {contentType application/xml}
 */

declare(strict_types=1);

use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


restore_error_handler();


$latte = new Latte\Engine;
$latte->setContentType($latte::CONTENT_XML);

$params['hello'] = '<i>Hello</i>';
$params['id'] = ':/item';
$params['people'] = ['John', 'Mary', 'Paul', ']]> <!--'];
$params['comment'] = 'test -- comment';
$params['el'] = new Html("<div title='1/2\"'></div>");
$params['xss'] = 'some&<>"\'/chars';
$params['mxss'] = '`mxss';

Assert::matchFile(
	__DIR__ . '/expected/contentType.xml.phtml',
	$latte->compile(__DIR__ . '/templates/contentType.xml.latte'),
);
Assert::matchFile(
	__DIR__ . '/expected/contentType.xml.html',
	$latte->renderToString(
		__DIR__ . '/templates/contentType.xml.latte',
		$params,
	),
);
