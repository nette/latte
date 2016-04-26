<?php

/**
 * Test: Latte\Filters
 */

use Latte\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MyFilter
{
	public static function invoke($s)
	{
		return strtolower($s);
	}
}


$filters = new Filters;

Assert::count(28, $filters->getAll());
Assert::same('Latte\Runtime\Filters::upper', $filters->getAll()['upper']);


$filters->add('f2', 'strtoupper');
Assert::same('AA', $filters->invoke('f2', ['aa']));


$filters->add('f1', [new MyFilter, 'invoke']);
Assert::same('aa', $filters->invoke('f1', ['aA']));


$filters->add('f3', 'MyFilter::invoke');
Assert::same('aa', $filters->invoke('f3', ['aA']));


$filters->add(NULL, function ($name, $val) {
	return $name === 'dynamic' ? "<$name $val>" : NULL;
});
$filters->add(NULL, function ($name, $val) {
	return $name === 'dynamic' ? "[$name $val]" : NULL;
});
$filters->add(NULL, function ($name, $val) use ($filters) {
	if ($name === 'dynamic2') {
		$filters->add($name, function ($val) {
			return "[$val]";
		});
	}
});

Assert::same('[dynamic aa]', $filters->invoke('dynamic', ['aa']));
Assert::exception(function () use ($filters) {
	$filters->invoke('unknown', ['']);
}, 'LogicException', "Filter 'unknown' is not defined.");

Assert::exception(function () use ($filters) {
	$filters->invoke('h3', ['']);
}, 'LogicException', "Filter 'h3' is not defined, did you mean 'f3'?");
