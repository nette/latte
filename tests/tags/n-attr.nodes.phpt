<?php declare(strict_types=1);

/**
 * n:attr
 */

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
					NAttr:
						Array:
							ArrayItem:
								String:
									value: title
								String:
									value: hello
	XX, exportTraversing('<input n:attr="title => hello">'));
