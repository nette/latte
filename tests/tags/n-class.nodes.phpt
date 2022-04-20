<?php

/**
 * n:class
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
				Fragment:
					NClass:
						Array:
							ArrayItem:
								String:
									value: title
								String:
									value: hello
	XX, exportTraversing('<input n:class="title => hello">'));
