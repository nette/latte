<?php

/**
 * Test: Latte\Essential\Filters::bytes()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('no locale', function () {
	$filters = new Filters;

	Assert::same('0 B', $filters->bytes(0.1));
	Assert::same('-1.03 GB', $filters->bytes(-1024 * 1024 * 1050));
	Assert::same('8881.78 PB', $filters->bytes(1e19));
});


test('with locale', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	Assert::same('0 B', $filters->bytes(0.1));
	Assert::same('-1,03 GB', $filters->bytes(-1024 * 1024 * 1050));
	Assert::same('8 881,78 PB', $filters->bytes(1e19));
});
