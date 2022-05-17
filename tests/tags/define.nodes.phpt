<?php

/**
 * Test: {define ...}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Define:
				String:
					value: test
				Fragment:
					Text:
						content: '...'
	XX, exportTraversing('{define test, $a, $b = new Foo}...{/define}'));
