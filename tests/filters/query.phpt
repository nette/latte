<?php

/**
 * Test: Latte\Runtime\Filters::query()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::query([]));
Assert::same('hello%2B%5B%5D', Filters::query('hello+[]'));
Assert::same(
	'0=0&a=a&arr%5B0%5D=1&arr%5B1%5D=2&arr%5B2%5D=3&true=1&false=0',
	Filters::query(['0' => 0, 'a' => 'a', 'arr' => [1, 2, 3], null => null, 'true' => true, 'false' => false])
);
