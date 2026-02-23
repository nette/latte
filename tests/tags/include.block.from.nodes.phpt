<?php declare(strict_types=1);

/**
 * Test: {include block from file}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			IncludeBlock:
				String:
					value: bl
				String:
					value: inc
				Array:
					ArrayItem:
						String:
							value: var
						Integer:
							value: 1
				Modifier:
					Filter:
						Identifier:
							name: trim
	XX, exportTraversing('{include bl from inc, var => 1|trim}'));
