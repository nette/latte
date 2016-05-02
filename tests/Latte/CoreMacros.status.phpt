<?php

/**
 * Test: Latte\Engine: {status}
 */

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'%A%header((isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.1") . " " . 200, TRUE, 200)%A%',
	$latte->compile('{status 200}')
);

Assert::match(
	'%A%if (!headers_sent()) header((isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.1") . " " . 300, TRUE, 300)%A%',
	$latte->compile('{status 300?}')
);
