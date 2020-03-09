<?php

/**
 * Test: Latte\PhpHelpers::dump()
 */

declare(strict_types=1);

use Latte\PhpHelpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('0', PhpHelpers::dump(0));
Assert::same('1', PhpHelpers::dump(1));
Assert::same('0.0', PhpHelpers::dump(0.0));
Assert::same('1.0', PhpHelpers::dump(1.0));
Assert::same('null', PhpHelpers::dump(null));
Assert::same('true', PhpHelpers::dump(true));
Assert::same('false', PhpHelpers::dump(false));

Assert::same("''", PhpHelpers::dump(''));
Assert::same("'Hello'", PhpHelpers::dump('Hello'));
Assert::same("'\t\n\t'", PhpHelpers::dump("\t\n\t"));

Assert::same('[1, 2, 3]', PhpHelpers::dump([1, 2, 3]));
Assert::same("['a']", PhpHelpers::dump(['a']));
Assert::same("[2 => 'a', 3 => 'b']", PhpHelpers::dump([2 => 'a', 'b']));
Assert::same("['k1' => 'a', 'k2' => [1, 2]]", PhpHelpers::dump(['k1' => 'a', 'k2' => [1, 2]]));

Assert::same("[\n\t1,\n\t2,\n\t3,\n]", PhpHelpers::dump([1, 2, 3], true));
Assert::same("[\n\t'a',\n]", PhpHelpers::dump(['a'], true));
