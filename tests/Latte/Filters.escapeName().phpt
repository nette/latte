<?php

/**
 * Test: Latte\Runtime\Filters::escapeName
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


# attribute names must start with a letter
Assert::same('invalid', Filters::escapeName(NULL));
Assert::same('invalid', Filters::escapeName(1));

Assert::same('a', Filters::escapeName('a'));
Assert::same('a111', Filters::escapeName('111a111'));
Assert::same('a', Filters::escapeName('"\'`a`\'"'));

# do not decode entities
Assert::same('lt', Filters::escapeName('&lt;'));
