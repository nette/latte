<?php

/**
 * Test: Latte\Essential\Filters::sanitizeUrl()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::sanitizeUrl(''));
Assert::same('', Filters::sanitizeUrl('http://'));
Assert::same('http://x', Filters::sanitizeUrl('http://x'));
Assert::same('http://x:80', Filters::sanitizeUrl('http://x:80'));
Assert::same('', Filters::sanitizeUrl('http://nette.org@1572395127'));
Assert::same('https://x', Filters::sanitizeUrl('https://x'));
Assert::same('ftp://x', Filters::sanitizeUrl('ftp://x'));
Assert::same('mailto:x', Filters::sanitizeUrl('mailto:x'));
Assert::same('/', Filters::sanitizeUrl('/'));
Assert::same('/a:b', Filters::sanitizeUrl('/a:b'));
Assert::same('//x', Filters::sanitizeUrl('//x'));
Assert::same('#aa:b', Filters::sanitizeUrl('#aa:b'));
Assert::same('', Filters::sanitizeUrl('data:'));
Assert::same('', Filters::sanitizeUrl('javascript:'));
Assert::same('', Filters::sanitizeUrl(' javascript:'));
Assert::same('javascript', Filters::sanitizeUrl('javascript'));
Assert::same('', Filters::sanitizeUrl(null));
Assert::same('1', Filters::sanitizeUrl(1));
