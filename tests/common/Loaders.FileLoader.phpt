<?php

/**
 * Test: FileLoader
 */

declare(strict_types=1);

use Latte\Loaders\FileLoader;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$loader = new FileLoader;
Assert::same(file_get_contents(__FILE__), $loader->load(__FILE__)->content);

Assert::same('/a/b/inner', strtr($loader->getReferredName('inner', '/a\b/c'), '\\', '/'));
Assert::same('/a/b/c', strtr($loader->getReferredName('/a/b/c', '/a/b/c'), '\\', '/'));
Assert::same('/a/c', strtr($loader->getReferredName('../c', '/a/b/c'), '\\', '/'));

Assert::same('/../c', strtr($loader->getReferredName('../c', '/a'), '\\', '/'));
Assert::same('w:/../c', strtr($loader->getReferredName('../c', 'w:\a'), '\\', '/'));
Assert::same('phar://file.phar/../c', strtr($loader->getReferredName('../c', 'phar://file.phar/a'), '\\', '/'));


$loader = new FileLoader;
Assert::exception(
	fn() => $loader->load('unknown'),
	Latte\TemplateNotFoundException::class,
	"Missing template file 'unknown'.",
);


$loader = new FileLoader(dirname(__DIR__));
Assert::same(file_get_contents(__FILE__), $loader->load('common/' . basename(__FILE__))->content);

Assert::exception(
	fn() => $loader->load('common/.././../file'),
	Latte\RuntimeException::class,
	"Template '%a%common/.././../file' is not within the allowed path '%a%'.",
);

Assert::same('common' . DIRECTORY_SEPARATOR . 'new', $loader->getReferredName('new', 'common/file'));
Assert::same('common', $loader->getReferredName('common', 'file'));
Assert::same('..' . DIRECTORY_SEPARATOR . 'tests', $loader->getReferredName('../tests', 'file'));
Assert::same('..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tests', $loader->getReferredName('../tests', '../file'));
Assert::same(str_repeat('..' . DIRECTORY_SEPARATOR, 7) . 'tests', $loader->getReferredName('../../../../tests', '../../../file'));
