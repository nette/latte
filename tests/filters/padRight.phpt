<?php

/**
 * Test: Latte\Essential\Filters::padLeft()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('ŽLUŤOUŤOUŤ', Filters::padRight("\u{17D}LU", 10, "\u{164}OU"));
Assert::same('ŽLUŤOUŤOU', Filters::padRight("\u{17D}LU", 9, "\u{164}OU"));
Assert::same('ŽLU', Filters::padRight("\u{17D}LU", 3, "\u{164}OU"));
Assert::same('ŽLU', Filters::padRight("\u{17D}LU", 0, "\u{164}OU"));
Assert::same('ŽLU', Filters::padRight("\u{17D}LU", -1, "\u{164}OU"));
Assert::same('ŽLUŤŤŤŤŤŤŤ', Filters::padRight("\u{17D}LU", 10, "\u{164}"));
Assert::same('ŽLU', Filters::padRight("\u{17D}LU", 3, "\u{164}"));
Assert::same('ŽLU       ', Filters::padRight("\u{17D}LU", 10));
