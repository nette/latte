<?php

/**
 * Test: Latte\Engine and n:ifcontent.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			IfContent:
				Fragment:
					Element:
						name: p
						Auxiliary:
						Fragment:
							Text:
								content: ''
						Fragment:
							Text:
								content: '...'
	XX, exportTraversing('<p n:ifcontent>...</p>'));
