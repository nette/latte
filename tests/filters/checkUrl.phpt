<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::checkUrl()
 */

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::checkUrl(''));
Assert::same('', Filters::checkUrl('http://'));
Assert::same('http://x', Filters::checkUrl('http://x'));
Assert::same('http://x:80', Filters::checkUrl('http://x:80'));
Assert::same('', Filters::checkUrl('http://nette.org@1572395127'));
Assert::same('https://x', Filters::checkUrl('https://x'));
Assert::same('ftp://x', Filters::checkUrl('ftp://x'));
Assert::same('mailto:x', Filters::checkUrl('mailto:x'));
Assert::same('/', Filters::checkUrl('/'));
Assert::same('/a:b', Filters::checkUrl('/a:b'));
Assert::same('//x', Filters::checkUrl('//x'));
Assert::same('#aa:b', Filters::checkUrl('#aa:b'));
Assert::same('', Filters::checkUrl('data:'));
Assert::same('', Filters::checkUrl('javascript:'));
Assert::same('', Filters::checkUrl(' javascript:'));
Assert::same('javascript', Filters::checkUrl('javascript'));
Assert::same('', Filters::checkUrl(null));
Assert::same('1', Filters::checkUrl(1));
