<?php

/**
 * Test: FileLoader
 */

declare(strict_types=1);

use Latte\Loaders\FileLoader;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$loader = new FileLoader;
Assert::same(file_get_contents(__FILE__), $loader->getContent(__FILE__));

Assert::false($loader->isExpired(__FILE__, filemtime(__FILE__)));
Assert::false($loader->isExpired(__FILE__, filemtime(__FILE__) + 1));
Assert::true($loader->isExpired(__FILE__, filemtime(__FILE__) - 1));

Assert::true($loader->isExpired('nonexist', filemtime(__FILE__)));

Assert::same('/a/b/inner', strtr($loader->getReferredName('inner', '/a\\b/c'), '\\', '/'));
Assert::same('/a/b/c', strtr($loader->getReferredName('/a/b/c', '/a/b/c'), '\\', '/'));
Assert::same('/a/c', strtr($loader->getReferredName('../c', '/a/b/c'), '\\', '/'));

Assert::exception(function () {
	$loader = new FileLoader;
	$loader->getContent('unknown');
}, Latte\RuntimeException::class, "Missing template file 'unknown'.");


$loader = new FileLoader(dirname(__DIR__));
Assert::same(file_get_contents(__FILE__), $loader->getContent('common/' . basename(__FILE__)));

Assert::exception(function () use ($loader) {
	$loader->getContent('common/.././../file');
}, Latte\RuntimeException::class, "Template '%a%common/.././../file' is not within the allowed path '%a%'.");

Assert::false($loader->isExpired('common/' . basename(__FILE__), filemtime(__FILE__) + 1));
Assert::true($loader->isExpired('common/' . basename(__FILE__), filemtime(__FILE__) - 1));

Assert::same('common' . DIRECTORY_SEPARATOR . 'new', $loader->getReferredName('new', 'common/file'));
Assert::same('common', $loader->getReferredName('common', 'file'));
Assert::same('..' . DIRECTORY_SEPARATOR . 'tests', $loader->getReferredName('../tests', 'file'));
Assert::same('..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tests', $loader->getReferredName('../tests', '../file'));
Assert::same(str_repeat('..' . DIRECTORY_SEPARATOR, 7) . 'tests', $loader->getReferredName('../../../../tests', '../../../file'));
