<?php declare(strict_types=1);

/**
 * Test: {include file}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Sandbox:
				String:
					value: inc1.latte
				Array:
					ArrayItem:
						String:
							value: var
						Integer:
							value: 1
	XX, exportTraversing('{sandbox inc1.latte, var => 1}'));
