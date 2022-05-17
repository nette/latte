<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			If:
				BinaryOp:
					operator: &&
					MethodCall:
						Variable:
							name: this
						Identifier:
							name: hasBlock
						Argument:
							String:
								value: foo
					Isset:
						Variable:
							name: item
				Fragment:
					Text:
						content: '.ifset.'
				Fragment:
					Text:
						content: '.else.'
	XX, exportTraversing('{ifset foo, $item}.ifset.{else}.else.{/ifset}'));
