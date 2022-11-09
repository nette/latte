<?php

/**
 * Test: comments HTML test
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$params['gt'] = '>';
$params['dash'] = '-';
$params['basePath'] = '/www';

Assert::matchFile(
	__DIR__ . '/expected/contentType.html.comments.html',
	$latte->renderToString(
		__DIR__ . '/templates/contentType.html.comments.latte',
		$params,
	),
);
