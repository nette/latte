<?php declare(strict_types=1);

/**
 * Test: {do}
 */

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
