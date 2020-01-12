<?php

/**
 * Test: n:nonce
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addProvider('coreNonce', null);

Assert::match(
	'<script></script>',
	$latte->renderToString('<script n:nonce></script>')
);


$latte->addProvider('coreNonce', 'djsdgidk');

Assert::match(
	'<script nonce="djsdgidk"></script>',
	$latte->renderToString('<script n:nonce></script>')
);
