<?php

/**
 * Test: {parameters}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Parameters:
				Assign:
					Variable:
						name: b
					Null:
				Assign:
					Variable:
						name: a
					Integer:
						value: 5
		Fragment:
	XX, exportTraversing('{parameters $b, int $a = 5}'));
