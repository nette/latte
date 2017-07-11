<?php

/**
 * Test: {debugbreak}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

if (!function_exists('debugbreak')) {
	function debugbreak()
	{
	}
}

Assert::match('%A%debugbreak();%A%', $latte->compile('{debugbreak}'));

Assert::match('%A%if ($i==1) debugbreak();%A%', $latte->compile('{debugbreak $i==1}'));
