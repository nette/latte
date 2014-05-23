<?php

/**
 * Test: Latte\Engine: {status}
 */

use Latte\Macros\CoreMacros,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match('%A%
<?php header((isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.1") . " " . 200, TRUE, 200) ?>

<?php if (!headers_sent()) header((isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.1") . " " . 300, TRUE, 300) ;
', $latte->compile('
{status 200}

{status 300?}
'));
