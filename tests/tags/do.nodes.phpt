<?php

/**
 * Test: {do}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Do:
				Assign:
					Variable:
						name: a
					Integer:
						value: 1
		Fragment:
	XX, exportTraversing('{do $a = 1}'));
