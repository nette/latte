<?php

/**
 * Test: {try} ... {else} {rollback} ... {/try}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Try:
				Fragment:
					Text:
						content: '.try.'
					Rollback:
					Text:
						content: '.rollback.'
				Fragment:
					Text:
						content: '.else.'
	XX, exportTraversing('{try}.try.{rollback}.rollback.{else}.else.{/try}'));
