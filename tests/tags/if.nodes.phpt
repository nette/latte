<?php

/**
 * Test: {if}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			If:
				Variable:
					name: a
				Fragment:
					Text:
						content: '.if.'
				If:
					Variable:
						name: b
					Fragment:
						Text:
							content: '.elseif.'
					Fragment:
						Text:
							content: '.else.'
	XX, exportTraversing('{if $a}.if.{elseif $b}.elseif.{else}.else.{/if}'));
