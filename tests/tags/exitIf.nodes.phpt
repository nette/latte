<?php

/**
 * Test: {exitIf}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
			Jump:
				Variable:
					name: a
		Fragment:
			Text:
				content: ' '
			Block:
				String:
					value: foo
				Modifier:
				Fragment:
					Jump:
						Variable:
							name: a

	XX, exportTraversing('{exitIf $a} {block foo}{exitIf $a}{/block}'));
