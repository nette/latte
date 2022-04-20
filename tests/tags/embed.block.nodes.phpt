<?php

/**
 * Test: {embed block}
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
					value: foo
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
	XX, exportTraversing('{embed foo, $var = 10} {block a/} {/embed}'));
