<?php

/**
 * Test: Latte\Runtime\Filters::reverse()
 */

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::reverse(''));
Assert::same('nøitæzilànôitânrëtñI', Filters::reverse("I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n")); // Iñtërnâtiônàlizætiøn
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
