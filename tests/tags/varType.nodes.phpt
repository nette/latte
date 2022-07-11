<?php

/**
 * Test: {varType}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			VarType:
				Variable:
					name: int
				SuperiorType:
					'int'
		Fragment:
	XX, exportTraversing('{varType int $int}'));
