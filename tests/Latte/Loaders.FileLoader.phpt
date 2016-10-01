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

Assert::same('/a/b/inner', strtr($loader->getReferredName('inner', '/a\\b/c'), '\\', '/'));
Assert::same('/a/b/c', strtr($loader->getReferredName('/a/b/c', '/a/b/c'), '\\', '/'));
Assert::same('/a/c', strtr($loader->getReferredName('../c', '/a/b/c'), '\\', '/'));

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

Assert::same('Latte' . DIRECTORY_SEPARATOR . 'new', $loader->getReferredName('new', 'Latte/file'));
Assert::same('Latte', $loader->getReferredName('Latte', 'file'));
Assert::same('..' . DIRECTORY_SEPARATOR . 'tests', $loader->getReferredName('../tests', 'file'));
Assert::same('..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tests', $loader->getReferredName('../tests', '../file'));
Assert::same(str_repeat('..' . DIRECTORY_SEPARATOR, 7) . 'tests', $loader->getReferredName('../../../../tests', '../../../file'));
