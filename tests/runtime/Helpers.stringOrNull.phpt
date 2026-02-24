<?php declare(strict_types=1);

use Latte\Runtime\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Helpers::stringOrNull(''));
Assert::same('x', Helpers::stringOrNull('x'));
Assert::null(Helpers::stringOrNull(0));
Assert::null(Helpers::stringOrNull(null));
