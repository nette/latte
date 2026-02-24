<?php declare(strict_types=1);

/**
 * Test: {varType}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			VarType:
		Fragment:
	XX, exportTraversing('{varType int $int}'));
