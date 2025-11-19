<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();

Assert::match(
	"%A%echo '{ }';%A%",
	$latte->compile('{l} {r}'),
);
