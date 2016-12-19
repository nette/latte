<?php

/**
 * Test: {status}
 */

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'%A%http_response_code(200)%A%',
	@$latte->compile('{status 200}') // @ macro is deprecated
);

Assert::match(
	'%A%if (!headers_sent()) http_response_code(300)%A%',
	@$latte->compile('{status 300?}')
);
