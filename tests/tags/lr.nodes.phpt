<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Text:
				content: '{'
			Text:
				content: ' '
			Text:
				content: '}'
	XX, exportTraversing('{l} {r}'));
