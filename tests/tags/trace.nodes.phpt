<?php declare(strict_types=1);

/**
 * Test: {trace}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Trace:
		Fragment:
	XX, exportTraversing('{trace}'));
