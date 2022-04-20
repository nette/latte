<?php

/**
 * Test: {php}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Do:
				Variable:
					name: var
		Fragment:
	XX, exportTraversing('{php $var}'));
