<?php declare(strict_types=1);

/**
 * Test: {while}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			While:
				Integer:
					value: 3
				Fragment:
					Text:
						content: '...'
	XX, exportTraversing('{while 3}...{/while}'));
