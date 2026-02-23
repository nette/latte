<?php declare(strict_types=1);

/**
 * Test: {define ...}
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Define:
				String:
					value: test
				Parameter:
					SuperiorType:
						'int'
					Variable:
						name: a
					Null:
				Parameter:
					Variable:
						name: b
					New:
						Name:
							name: Foo
				Fragment:
					Text:
						content: '...'
	XX, exportTraversing('{define test, int $a, $b = new Foo}...{/define}'));
