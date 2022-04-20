<?php

/**
 * Test: {for}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			For:
				Assign:
					Variable:
						name: i
					Integer:
						value: 0
				BinaryOp:
					operator: <
					Variable:
						name: i
					Integer:
						value: 10
				PostOp:
					Variable:
						name: i
				Fragment:
					Text:
						content: '...'
	XX, exportTraversing('{for $i = 0; $i < 10; $i++}...{/for}'));
