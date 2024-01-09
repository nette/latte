<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Print:
				Variable:
					name: var
				Modifier:
					Filter:
						Identifier:
							name: trim
					Filter:
						Identifier:
							name: upper
	XX, exportTraversing('{=$var|trim|upper}'));


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Print:
				FilterCall:
					FilterCall:
						Variable:
							name: var
						Filter:
							Identifier:
								name: trim
					Filter:
						Identifier:
							name: upper
				Modifier:
	XX, exportTraversing('{=($var|trim|upper)}'));
