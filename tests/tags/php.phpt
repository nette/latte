<?php

/**
 * Test: {php}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addExtension(new Latte\Essential\RawPhpExtension);

Assert::match(
	<<<'XX'
		%A%
				/* line 1 */;
				if ($a) {
					echo 10;
				}
		%A%
		XX,
	$latte->compile('{php if ($a) { echo 10; }}'),
);
