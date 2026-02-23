<?php declare(strict_types=1);

/**
 * Test: {exitIf}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Jump:
				Variable:
					name: a
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
