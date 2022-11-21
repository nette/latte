<?php

/**
 * Test: {contentType text/plain}
 */

declare(strict_types=1);

use Latte\ContentType;
use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


restore_error_handler();


$latte = new Latte\Engine;
$latte->setContentType(ContentType::Text);

$params['foo'] = 'hello';

Assert::matchFile(
	__DIR__ . '/expected/contentType.text.php',
	$latte->compile(__DIR__ . '/templates/contentType.text.latte'),
);
Assert::matchFile(
	__DIR__ . '/expected/contentType.text.txt',
	$latte->renderToString(
		__DIR__ . '/templates/contentType.text.latte',
		$params,
	),
);
