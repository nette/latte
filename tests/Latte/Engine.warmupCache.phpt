<?php

/**
 * Test: Latte\Engine: {warmupCache}
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

test(function () {
	$precachedDir =__DIR__.'/../tmp/';
	$template = __DIR__.'/templates/general.latte';

	$latte = new Latte\Engine;
	$latte->setTempDirectory($precachedDir);

	$cachedFile = $latte->getCacheFile($template);
	@unlink($cachedFile);
	Assert::false(file_exists($cachedFile));

	$latte->warmupCache($template);
	Assert::true(file_exists($cachedFile));
});
