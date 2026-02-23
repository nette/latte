<?php declare(strict_types=1);

/**
 * Test: comments HTML test
 */

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


// no escape
$latte->setLoader(new Latte\Loaders\StringLoader);
Assert::exception(
	fn() => $latte->renderToString('<!-- {="-->"|noescape} -->'),
	Latte\CompileException::class,
	'Using |noescape is not allowed in this context (on line 1 at column 13)',
);
