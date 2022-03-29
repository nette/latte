<?php

/**
 * Test: {php}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->addExtension(new Latte\Essential\RawPhpExtension);

Assert::match(<<<'XX'
	Template:
		Fragment:
			RawPhp:
		Fragment:
	XX, exportTraversing('{php $var}', $latte));
