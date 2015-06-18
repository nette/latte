<?php

/**
 * Test: Latte\Runtime\Filters::indent()
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('',  Filters::indent(''));
Assert::same("\n",  Filters::indent("\n"));
Assert::same("\tword",  Filters::indent('word'));
Assert::same("\n\tword",  Filters::indent("\nword"));
Assert::same("\n\tword",  Filters::indent("\nword"));
Assert::same("\n\tword\n",  Filters::indent("\nword\n"));
Assert::same("\r\n\tword\r\n",  Filters::indent("\r\nword\r\n"));
Assert::same("\r\n\t\tword\r\n",  Filters::indent("\r\nword\r\n", 2));
Assert::same("\r\n      word\r\n",  Filters::indent("\r\nword\r\n", 2, '   '));
