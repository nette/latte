<?php declare(strict_types=1);

use Latte\Tools\Linter;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('warnings pass by default', function () {
	$linter = new Linter;
	Assert::true($linter->lintLatte(__DIR__ . '/templates/unknown.latte'));
});


test('warnings fail with failOnWarnings', function () {
	$linter = new Linter(failOnWarnings: true);
	Assert::false($linter->lintLatte(__DIR__ . '/templates/unknown.latte'));
});


test('no warnings pass with failOnWarnings', function () {
	$linter = new Linter(failOnWarnings: true);
	Assert::true($linter->lintLatte(__DIR__ . '/templates/known.latte'));
});


test('BOM fails with failOnWarnings', function () {
	$file = tempnam(sys_get_temp_dir(), 'latte-');
	file_put_contents($file, "\xEF\xBB\xBF" . '{$text}');
	try {
		$linter = new Linter(failOnWarnings: true);
		Assert::false($linter->lintLatte($file));
	} finally {
		unlink($file);
	}
});
