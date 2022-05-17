<?php

/**
 * Test: {ifchanged}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			IfChanged:
				Array:
					ArrayItem:
						Integer:
							value: 3
				Fragment:
					Text:
						content: '...'
	XX, exportTraversing('{ifchanged 3}...{/ifchanged}'));
