<?php

/**
 * Test: Latte\Engine: {debugbreak}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

if (!function_exists('debugbreak')) {
	function debugbreak() {}
}

Assert::match('%A%
<?php debugbreak() ;if ($i==1) debugbreak() ;
%A%
', $latte->compile('
{debugbreak}
{debugbreak $i==1}
'));
