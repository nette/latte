<?php

/**
 * Test: {import ...}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Import:
				Variable:
					name: var
		Fragment:
	XX, exportTraversing('{import $var}'));
