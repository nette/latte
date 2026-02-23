<?php declare(strict_types=1);

/**
 * Test: {debugbreak}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

if (!function_exists('xdebug_break')) {
	function xdebug_break()
	{
	}
}

Assert::match('%A%xdebug_break()%A%', $latte->compile('{debugbreak}'));

Assert::match('%A%if ($i == 1) xdebug_break()%A%', $latte->compile('{debugbreak $i==1}'));
