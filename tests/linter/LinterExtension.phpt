<?php declare(strict_types=1);

use Latte\Tools\LinterExtension;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::noError(function () {
	$latte = createLatte();
	$latte->addExtension(new LinterExtension);
	$latte->compile(file_get_contents(__DIR__ . '/templates/known.latte'));
});


Assert::error(
	function () {
		$latte = createLatte();
		$latte->addExtension(new LinterExtension);
		$latte->compile(file_get_contents(__DIR__ . '/templates/unknown.latte'));
	},
	[
		[E_USER_WARNING, 'Unknown filter |unknownFilter on line 2 at column 7'],
		[E_USER_WARNING, 'Unknown function unknownFunction() on line 3 at column 3'],
		[E_USER_WARNING, 'Unknown function unknownFunction() on line 4 at column 3'],
		[E_USER_WARNING, 'Unknown class UnknownClass on line 5 at column 13'],
		[E_USER_WARNING, 'Unknown method DateTime::unknownMethod() on line 6 at column 3'],
		[E_USER_WARNING, 'Unknown method DateTime::unknownMethod() on line 7 at column 3'],
		[E_USER_WARNING, 'Unknown class constant DateTime::UNKNOWN_CONSTANT on line 8 at column 3'],
		[E_USER_WARNING, 'Unknown constant UNKNOWN_GLOBAL_CONSTANT on line 9 at column 3'],
		[E_USER_WARNING, 'Unknown class UnknownClass in instanceof on line 10 at column 5'],
		[E_USER_WARNING, 'Unknown static property DateTime::$unknownProperty on line 11 at column 3'],
		[E_USER_WARNING, 'Unknown class UnknownClass on line 12 at column 3'],
	],
);
