<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Cache::loadOrCreate() and a class declared meanwhile by another coroutine
 */

use Latte\Runtime\Cache;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test('does not include the file again when the class is already declared', function () {
	$template = __DIR__ . '/templates/block.latte';

	$dir = getTempDir();
	$latte = new Latte\Engine;
	$latte->setCacheDirectory($dir);
	$latte->warmupCache($template);
	Assert::true(class_exists($latte->getTemplateClass($template), false));

	$cache = new Cache;
	$cache->directory = $dir;
	Assert::noError(fn() => $cache->loadOrCreate($latte, $template));
});
