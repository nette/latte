<?php declare(strict_types=1);

/**
 * n:tag
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Element:
				name: input
				Tag:
					Variable:
						name: var
				Fragment:
					Text:
						content: ''
	XX, exportTraversing('<input n:tag="$var">'));
