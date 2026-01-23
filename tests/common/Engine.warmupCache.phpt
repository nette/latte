<?php declare(strict_types=1);

/**
 * Test: Latte\Engine::warmupCache()
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test('', function () {
	$template = __DIR__ . '/templates/block.latte';

	$latte = new Latte\Engine;
	$latte->setCacheDirectory(getTempDir());

	$cachedFile = $latte->getCacheFile($template);
	Assert::false(file_exists($cachedFile));

	$latte->warmupCache($template);
	Assert::true(file_exists($cachedFile));
});
