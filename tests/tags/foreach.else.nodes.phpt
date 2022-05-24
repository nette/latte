<?php

/**
 * Test: foreach + else
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Foreach:
				Variable:
					name: a
				Variable:
					name: b
				Variable:
					name: c
				Fragment:
					Text:
						content: '.then.'
				Fragment:
					Text:
						content: '.else.'
	XX, exportTraversing('{foreach $a as $b => $c}.then.{else}.else.{/foreach}'));
