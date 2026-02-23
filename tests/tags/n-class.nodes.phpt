<?php declare(strict_types=1);

/**
 * n:class
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Element:
				name: input
				Fragment:
					NClass:
						Array:
							ArrayItem:
								String:
									value: title
								String:
									value: hello
	XX, exportTraversing('<input n:class="title => hello">'));
