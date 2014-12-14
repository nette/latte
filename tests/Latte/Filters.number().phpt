<?php

/**
 * Test: Latte\Runtime\Filters::number()
 */

use Latte\Runtime\Filters,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$input = 123976.156984;

Assert::same( "123,976", Filters::number($input) );

Filters::$numberFormat = array(0, ',', ' ');
Assert::same( "123 976", Filters::number($input) );

Filters::$numberFormat = array(2, ',', ' ');
Assert::same( "123 976,16", Filters::number($input) );

Filters::$numberFormat = array(6, ',', ' ');
Assert::same( "123 976,156984", Filters::number($input) );
