<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::dataStream
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$data = 'abc';
$type = 'text/plain';
$expected = 'data:text/plain;base64,' . base64_encode($data);
Assert::same($expected, Filters::dataStream($data, $type));
Assert::true(str_starts_with(Filters::dataStream($data), 'data:'));
