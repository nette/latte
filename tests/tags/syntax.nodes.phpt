<?php

/**
 * Test: {syntax ...}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
	XX, exportTraversing('{syntax double}'));
