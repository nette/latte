<?php

/**
 * Test: FileLoader
 */

use Tester\Assert;
use Latte\Loaders\FileLoader;


require __DIR__ . '/../bootstrap.php';


$loader = new FileLoader;
Assert::same(file_get_contents(__FILE__), $loader->getContent(__FILE__));

Assert::false($loader->isExpired(__FILE__, filemtime(__FILE__)));
Assert::false($loader->isExpired(__FILE__, filemtime(__FILE__) + 1));
Assert::true($loader->isExpired(__FILE__, filemtime(__FILE__) - 1));

Assert::same(__DIR__ . '/inner', $loader->getChildName('inner', __FILE__));
Assert::same(__FILE__, $loader->getChildName(__FILE__, __FILE__));

Assert::exception(function () {
	$loader = new FileLoader;
	$loader->getContent('unknown');
}, RuntimeException::class, "Missing template file 'unknown'.");
