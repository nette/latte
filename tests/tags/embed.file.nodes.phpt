<?php

/**
 * Test: {embed file}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Embed:
				String:
					value: foo.latte
				Array:
					ArrayItem:
						Assign:
							Variable:
								name: var
							Integer:
								value: 10
				Fragment:
					Text:
						content: ' '
					Block:
						String:
							value: a
						Modifier:
						Fragment:
					Text:
						content: ' '
	XX, exportTraversing('{embed foo.latte, $var = 10} {block a/} {/embed}'));
