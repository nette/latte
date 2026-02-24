<?php declare(strict_types=1);

/**
 * Test: {spaceless}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Spaceless:
				Fragment:
					Text:
						content: '...'
	XX, exportTraversing('{spaceless}...{/spaceless}'));
