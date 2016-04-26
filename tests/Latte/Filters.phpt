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


test(function () {
	$filters = new Filters;

	Assert::count(28, $filters->getAll());
	Assert::same('Latte\Runtime\Filters::upper', $filters->getAll()['upper']);

	$filters->add('f1', 'strtoupper');
	Assert::same('AA', $filters->invoke('f1', ['aa']));

	$filters->add('f2', [new MyFilter, 'invoke']);
	Assert::same('aa', $filters->invoke('f2', ['aA']));

	$filters->add('f3', 'MyFilter::invoke');
	Assert::same('aa', $filters->invoke('f3', ['aA']));

	Assert::exception(function () use ($filters) {
		$filters->invoke('h3', ['']);
	}, 'LogicException', "Filter 'h3' is not defined, did you mean 'f3'?");
});


test(function () {
	$filters = new Filters;
	$filters->add(NULL, function ($name, $val) {
		return implode(',', func_get_args());
	});
	Assert::same('dynamic,1,2', $filters->invoke('dynamic', [1, 2]));
	Assert::same('another,1,2', $filters->invoke('another', [1, 2]));
});


test(function () {
	$filters = new Filters;
	$filters->add(NULL, function ($name, $val) use ($filters) {
		if ($name === 'dynamic') {
			$filters->add($name, function ($val) {
				return implode(',', func_get_args());
			});
		}
	});
	Assert::same('1,2', $filters->invoke('dynamic', [1, 2]));
	Assert::same('1,2', $filters->invoke('dynamic', [1, 2]));

	Assert::exception(function () use ($filters) {
		$filters->invoke('unknown', ['']);
	}, 'LogicException', "Filter 'unknown' is not defined.");
});
