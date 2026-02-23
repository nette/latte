<?php declare(strict_types=1);

use Latte\Tools\LinterExtension;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('detects unknown filters, functions, classes, methods, constants', function () {
	$warnings = [];
	set_error_handler(function (int $severity, string $message) use (&$warnings) {
		if ($severity === E_USER_WARNING) {
			$warnings[] = $message;
			return true;
		}
		return false;
	});

	try {
		$latte = createLatte();
		$latte->addExtension(new LinterExtension);
		$latte->compile(file_get_contents(__DIR__ . '/templates/unknown.latte'));
	} finally {
		restore_error_handler();
	}

	Assert::same([
		'Unknown filter |unknownFilter on line 13 at column 7',
		'Unknown function unknownFunction() on line 14 at column 3',
		'Unknown function unknownFunction() on line 15 at column 3',
		'Unknown class UnknownClass on line 16 at column 13',
		'Unknown method DateTime::unknownMethod() on line 17 at column 3',
		'Unknown method DateTime::unknownMethod() on line 18 at column 3',
		'Unknown class constant DateTime::UNKNOWN_CONSTANT on line 19 at column 3',
		'Unknown constant UNKNOWN_GLOBAL_CONSTANT on line 20 at column 3',
		'Unknown class UnknownClass in instanceof on line 21 at column 5',
		'Unknown static property DateTime::$unknownProperty on line 22 at column 3',
	], $warnings);
});
