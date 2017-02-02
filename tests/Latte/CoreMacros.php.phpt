<?php

/**
 * Test: {php}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'%A%$a = \'test\' ? [] : NULL%A%',
	$latte->compile('
{php}
{php $a = test ? []}
'));

Assert::match(
	'%A%$a = \'test\' ? [] : NULL%A%',
	$latte->compile('
{php}
{php $a = test ? []}
'));
