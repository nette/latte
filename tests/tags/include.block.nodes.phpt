<?php

/**
 * Test: {include block}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			IncludeBlock:
				String:
					value: block
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
	XX, exportTraversing('{include block, var => 1|trim}'));
