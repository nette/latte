<?php declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('unreadable file leaves the error handler as it was', function () {
	$handler = fn() => true; // swallows the warning of the failed read
	set_error_handler($handler);
	try {
		Assert::false((new Latte\Tools\Linter)->lintLatte(__DIR__ . '/missing.latte'));
		Assert::same($handler, set_error_handler(null));
		restore_error_handler();
	} finally {
		restore_error_handler();
	}
});
