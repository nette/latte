<?php

/**
 * Test: {debugbreak}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Debugbreak:
				BinaryOp:
					operator: ==
					Variable:
						name: i
					Integer:
						value: 1
		Fragment:
	XX, exportTraversing('{debugbreak $i==1}'));
