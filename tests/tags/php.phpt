<?php

/**
 * Test: {php}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->addExtension(new Latte\Essential\RawPhpExtension);

Assert::match(
	<<<'XX'
		%A%
				/* pos 1:1 */;
				if ($a) {
					echo 10;
				}
		%A%
		XX,
	$latte->compile('{php if ($a) { echo 10; }}'),
);
