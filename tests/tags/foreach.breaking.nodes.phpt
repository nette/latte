<?php

/**
 * Test: {foreach} + {continueIf}, {breakIf}, {skipIf}
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
				Fragment:
					Text:
						content: '.1.'
					Skip:
						Variable:
							name: a
					Text:
						content: '.2.'
					Skip:
						Variable:
							name: b
					Text:
						content: '.3.'
					Skip:
						Variable:
							name: c
					Text:
						content: '.4.'
	XX, exportTraversing('{foreach $a as $b}.1.{continueIf $a}.2.{breakIf $b}.3.{skipIf $c}.4.{/foreach}'));
