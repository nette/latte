<?php

/**
 * Test: {trace}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Trace:
		Fragment:
	XX, exportTraversing('{trace}'));
