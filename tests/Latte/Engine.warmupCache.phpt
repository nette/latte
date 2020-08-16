<?php

/**
 * Test: Latte\Engine::warmupCache()
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

test('', function () {
	$template = __DIR__ . '/templates/general.latte';

	$latte = new Latte\Engine;
	$latte->setTempDirectory(getTempDir());

	$cachedFile = $latte->getCacheFile($template);
	Assert::false(file_exists($cachedFile));

	$latte->warmupCache($template);
	Assert::true(file_exists($cachedFile));
});
