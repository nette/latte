<?php

/**
 * Test: Latte\Essential\CoreExtension::getFunctions()
 */

declare(strict_types=1);

use Latte\Essential\CoreExtension;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$ext = new CoreExtension;
$functions = $ext->getFunctions();
Assert::same(1, ($functions['clamp'])(1, 1, 1));
Assert::same(true, ($functions['divisibleBy'])(4, 2));
Assert::same(true, ($functions['even'])(2));
Assert::same('abc', ($functions['first'])(['abc']));
Assert::type(Latte\Essential\AuxiliaryIterator::class, ($functions['group'])([], fn($a) => $a * 10));
Assert::same('abc', ($functions['last'])(['abc']));
Assert::same(true, ($functions['odd'])(1));
Assert::same(['abc'], ($functions['slice'])(['abc'], 0, 1));
