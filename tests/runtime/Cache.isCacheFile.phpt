<?php

/**
 * Test: Latte\Runtime\Cache::isCacheFile()
 */

declare(strict_types=1);

use Latte\Runtime\Cache;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('recognizes generated cache file names regardless of hash length', function () {
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader(['main' => 'x']));
	$latte->setCacheDirectory(getTempDir());

	Assert::true(Cache::isCacheFile($latte->getCacheFile('main')));
	Assert::true(Cache::isCacheFile('/tmp/page-latte--0123456789abcdef.php'));
	Assert::true(Cache::isCacheFile('/tmp/sub.dir/x.latte--0123456789.php'));
});


test('rejects other files', function () {
	Assert::false(Cache::isCacheFile('/tmp/page.latte'));
	Assert::false(Cache::isCacheFile('/tmp/latte--0123456789abcdef.php.lock'));
	Assert::false(Cache::isCacheFile('/tmp/other.php'));
	Assert::false(Cache::isCacheFile(''));
});
