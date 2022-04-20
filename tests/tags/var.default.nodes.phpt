<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Var:
				Assign:
					Variable:
						name: var
					Null:
				Assign:
					Variable:
						name: var2
					Integer:
						value: 3
		Fragment:
	XX, exportTraversing('{var $var, int|array $var2 = 3}'));
