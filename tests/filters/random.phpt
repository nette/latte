<?php

/**
 * Test: Latte\Extensions\Filters::random()
 */

declare(strict_types=1);

use Latte\Extensions\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::null(Filters::random(''));
Assert::null(Filters::random([]));

$item = Filters::random(['a', 'b']);
Assert::true($item === 'a' || $item === 'b');

$item = Filters::random('žý');
Assert::true($item === 'ž' || $item === 'ý');
