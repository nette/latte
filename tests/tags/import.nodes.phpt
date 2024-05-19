<?php

/**
 * Test: {import ...}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Import:
				Variable:
					name: var
				Array:
		Fragment:
	XX, exportTraversing('{import $var}'));


Assert::match(<<<'XX'
	Template:
		Fragment:
			Import:
				Variable:
					name: var
				Array:
					ArrayItem:
						Identifier:
							name: param
						String:
							value: val
		Fragment:
	XX, exportTraversing('{import $var, param: val}'));
