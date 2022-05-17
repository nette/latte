<?php

/**
 * Test: {switch}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Switch:
				Integer:
					value: 3
				Array:
					ArrayItem:
						Integer:
							value: 1
					ArrayItem:
						Integer:
							value: 2
				Fragment:
					Text:
						content: '.case.'
				Fragment:
					Text:
						content: '.default.'
	XX, exportTraversing('{switch 3}  {case 1, 2}.case.{default}.default.{/switch}'));
