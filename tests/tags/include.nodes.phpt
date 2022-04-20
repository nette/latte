<?php

/**
 * Test: {include file}
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
					ArrayItem:
						String:
							value: var
						Integer:
							value: 1
				Modifier:
					Filter:
						Identifier:
							name: trim
			Text:
				content: ' '
	XX, exportTraversing('{include file.latte, var => 1|trim} '));
