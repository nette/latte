<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::explode()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same([], Filters::explode(''));
Assert::same(['a'], Filters::explode('a'));
Assert::same(['ž', 'ý'], Filters::explode('žý'));

Assert::same([''], Filters::explode('', ','));
Assert::same(['a'], Filters::explode('a', ','));
Assert::same(['a', ''], Filters::explode('a,', ','));
Assert::same(['a', '', 'b'], Filters::explode('a,,b', ','));

Assert::exception(
	fn() => Filters::explode("a\xC3\x28b"), // invalid UTF-8
	Latte\RuntimeException::class,
	'%a?%UTF-8%a?%',
);
