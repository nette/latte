<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
Assert::contains('declare(strict_types=1)', $latte->compile(''));

$latte->setStrictTypes(false);
Assert::notContains('declare(strict_types=1)', $latte->compile(''));

Assert::noError(fn() => $latte->render(''));
