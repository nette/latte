<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
Assert::notContains('declare(strict_types=1)', $latte->compile(''));

$latte->setStrictTypes(true);
Assert::contains('declare(strict_types=1)', $latte->compile(''));

Assert::noError(fn() => $latte->render(''));
