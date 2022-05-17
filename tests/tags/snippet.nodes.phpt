<?php

/**
 * Test: general snippets test.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Snippet:
				Variable:
					name: name
				Fragment:
					Element:
						name: div
						Auxiliary:
						Fragment:
							Auxiliary:
						Fragment:
							Text:
								content: '...'
	XX, exportTraversing('<div n:snippet=$name>...</div>'));
