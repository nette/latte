<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::random()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::null(Filters::random(''));
Assert::null(Filters::random([]));

$item = Filters::random(['a', 'b']);
Assert::true($item === 'a' || $item === 'b');

$item = Filters::random('žý');
Assert::true($item === 'ž' || $item === 'ý');

// iterables
Assert::same(7, Filters::random(new ArrayIterator([7])));
Assert::null(Filters::random(new ArrayIterator([])));
