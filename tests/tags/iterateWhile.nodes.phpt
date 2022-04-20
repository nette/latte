<?php

/**
 * Test: {iterateWhile}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Auxiliary:
		Fragment:
			Foreach:
				Variable:
					name: a
				Variable:
					name: b
				Fragment:
					IterateWhile:
						Variable:
							name: cond
						Fragment:
							Text:
								content: '...'
	XX, exportTraversing('{foreach $a as $b}{iterateWhile $cond}...{/iterateWhile}{/foreach}'));
