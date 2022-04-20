<?php

/**
 * Test: {first}, {last}, {sep}.
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
					FirstLastSep:
						Integer:
							value: 3
						Fragment:
							Text:
								content: 'first'
					Text:
						content: ' '
					FirstLastSep:
						Integer:
							value: 3
						Fragment:
							Text:
								content: ','
					Text:
						content: ' '
					FirstLastSep:
						Integer:
							value: 3
						Fragment:
							Text:
								content: 'last'
	XX, exportTraversing('{foreach $a as $b}{first 3}first{/first} {sep 3},{/sep} {last 3}last{/last}{/foreach}'));
