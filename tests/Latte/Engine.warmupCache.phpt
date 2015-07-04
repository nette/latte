<?php

/**
 * Test: Latte\Engine::warmupCache()
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

test(function () {
	$template = __DIR__ . '/templates/general.latte';

	$latte = new Latte\Engine;
	$latte->setTempDirectory(TEMP_DIR);

	$cachedFile = $latte->getCacheFile($template);
	Assert::false(file_exists($cachedFile));

	$latte->warmupCache($template);
	Assert::true(file_exists($cachedFile));
});
