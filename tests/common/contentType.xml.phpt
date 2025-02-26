<?php

/**
 * Test: {contentType application/xml}
 */

declare(strict_types=1);

use Latte\ContentType;
use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::same(
	' &lt;&gt;&quot;&apos;',
	$latte->renderToString('{contentType xml} {$foo}', ['foo' => '<>"\'']),
);

Assert::same(
	' &lt;&gt;&quot;&apos;',
	$latte->renderToString('{contentType application/xml} {$foo}', ['foo' => '<>"\'']),
);


$latte = new Latte\Engine;
$latte->setContentType(ContentType::Xml);

$params['hello'] = '<i>Hello</i>';
$params['id'] = ':/item';
$params['people'] = ['John', 'Mary', 'Paul', ']]> <!--'];
$params['comment'] = 'test -- comment';
$params['el'] = new Html("<div title='1/2\"'></div>");
$params['xss'] = 'some&<>"\'/chars';

Assert::matchFile(
	__DIR__ . '/expected/contentType.xml.php',
	$latte->compile(__DIR__ . '/templates/contentType.xml.latte'),
);
Assert::matchFile(
	__DIR__ . '/expected/contentType.xml.html',
	$latte->renderToString(
		__DIR__ . '/templates/contentType.xml.latte',
		$params,
	),
);
