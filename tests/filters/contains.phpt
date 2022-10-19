<?php

/**
 * Test: Latte\Runtime\Filters::contains
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::false(Filters::contains(null, []));
Assert::true(Filters::contains(null, [null]));
Assert::false(Filters::contains(1, ['1']));
Assert::true(Filters::contains(1, [1]));

Assert::true(Filters::contains('', ''));
Assert::true(Filters::contains('', 'abcd'));
Assert::false(Filters::contains('bc', ''));
Assert::true(Filters::contains('bc', 'abcd'));
Assert::true(Filters::contains(null, ''));
Assert::true(Filters::contains(1, '123'));
Assert::false(Filters::contains(1, '23'));
