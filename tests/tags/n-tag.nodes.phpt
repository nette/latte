<?php

/**
 * n:tag
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Element:
				name: input
				Auxiliary:
				StaticCall:
					Name:
						parts: ['Latte', 'Essential', 'Nodes', 'NTagNode']
					Identifier:
						name: check
					Argument:
						String:
							value: input
					Argument:
						Variable:
							name: var
				Fragment:
					Text:
						content: ''
	XX, exportTraversing('<input n:tag="$var">'));
