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
		Fragment:
	XX, exportTraversing('{varType int $int}'));
