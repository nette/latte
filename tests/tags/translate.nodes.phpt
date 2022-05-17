<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Translate:
				Fragment:
					Text:
						content: '...'
				Modifier:
					Filter:
						Identifier:
							name: trim
	XX, exportTraversing('{translate|trim}...{/translate}'));
