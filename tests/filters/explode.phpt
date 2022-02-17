<?php

/**
 * Test: Latte\Extensions\Filters::explode()
 */

declare(strict_types=1);

use Latte\Extensions\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same([], Filters::explode(''));
Assert::same(['a'], Filters::explode('a'));
Assert::same(['ž', 'ý'], Filters::explode('žý'));

Assert::same([''], Filters::explode('', ','));
Assert::same(['a'], Filters::explode('a', ','));
Assert::same(['a', ''], Filters::explode('a,', ','));
Assert::same(['a', '', 'b'], Filters::explode('a,,b', ','));
