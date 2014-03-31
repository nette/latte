<?php

/**
 * Test: Nette\Latte\Runtime\Filters::trim()
 *
 * @author     David Grudl
 */

use Nette\Latte\Runtime\Filters,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( 'x',  Filters::trim(" \t\n\r\x00\x0B\xC2\xA0x") );
Assert::same( 'a b',  Filters::trim(' a b ') );
Assert::same( ' a b ',  Filters::trim(' a b ', '') );
Assert::same( 'e',  Filters::trim("\xc5\x98e-", "\xc5\x98-") ); // Ře-

Assert::exception(function() {
	Filters::trim("\xC2x\xA0");
}, 'Nette\Latte\RegexpException', NULL);
