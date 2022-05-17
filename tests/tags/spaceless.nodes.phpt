<?php

/**
 * Test: {spaceless}
 */

declare(strict_types=1);

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
