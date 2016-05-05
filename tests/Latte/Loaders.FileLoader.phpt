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

Assert::same(__DIR__ . '/inner', $loader->getReferredName('inner', __FILE__));
Assert::same(__FILE__, $loader->getReferredName(__FILE__, __FILE__));

Assert::exception(function () {
	$loader = new FileLoader;
	$loader->getContent('unknown');
}, 'RuntimeException', "Missing template file 'unknown'.");


$loader = new FileLoader(dirname(__DIR__));
Assert::same(file_get_contents(__FILE__), $loader->getContent('Latte/' . basename(__FILE__)));

Assert::exception(function () use ($loader) {
	$loader->getContent('Latte/.././../file');
}, 'RuntimeException', "Template '%a%Latte/.././../file' is not within the allowed path '%a%'.");

Assert::false($loader->isExpired('Latte/' . basename(__FILE__), filemtime(__FILE__) + 1));
Assert::true($loader->isExpired('Latte/' . basename(__FILE__), filemtime(__FILE__) - 1));

Assert::same('Latte/' . basename(__FILE__), $loader->getReferredName(basename(__FILE__), 'Latte/file'));
Assert::same('Latte', $loader->getReferredName('Latte', 'file'));
Assert::same('../tests', $loader->getReferredName('../tests', 'file'));
