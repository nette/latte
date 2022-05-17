<?php

/**
 * Test: {include ... with blocks}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			IncludeFile:
				String:
					value: file.latte
				Array:
				Modifier:
					Filter:
						Identifier:
							name: trim
	XX, exportTraversing('{include file.latte with blocks|trim}'));
