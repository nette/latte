<?php

/**
 * Test: Latte\Runtime\Filters::random()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::null(Filters::random(''));
Assert::null(Filters::random([]));

$item = Filters::random(['a', 'b']);
Assert::true($item === 'a' || $item === 'b');

$item = Filters::random('žý');
Assert::true($item === 'ž' || $item === 'ý');
