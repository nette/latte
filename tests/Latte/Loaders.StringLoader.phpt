<?php

/**
 * Test: StringLoader
 */

use Tester\Assert;
use Latte\Loaders\StringLoader;


require __DIR__ . '/../bootstrap.php';


$loader = new StringLoader;
Assert::same('content', $loader->getContent('content'));

Assert::false($loader->isExpired('content', 0));
Assert::false($loader->isExpired('content', 1));

Assert::same('inner', $loader->getChildName('inner', 'outer'));
