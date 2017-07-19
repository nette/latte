<?php

/**
 * Test: Latte\Runtime\Filters::reverse()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::reverse(''));
Assert::same("n\u{F8}it\u{E6}zil\u{E0}n\u{F4}it\u{E2}nr\u{EB}t\u{F1}I", Filters::reverse("I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n")); // Iñtërnâtiônàlizætiøn
Assert::same(['two', 'one'], Filters::reverse(['one', 'two']));
Assert::same([1 => 'two', 0 => 'one'], Filters::reverse(['one', 'two'], true));


class TraversableClass implements IteratorAggregate
{
	public function getIterator()
	{
		return new ArrayIterator(['one', 'two', 'three']);
	}
}

Assert::same(['three', 'two', 'one'], Filters::reverse(new TraversableClass));
Assert::same([2 => 'three', 1 => 'two', 0 => 'one'], Filters::reverse(new TraversableClass, true));
