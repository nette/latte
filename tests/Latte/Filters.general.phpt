<?php

/**
 * Test: filters test.
 */

declare(strict_types=1);

use Latte\Runtime\FilterInfo;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class MyFilter
{
	protected $count = 0;


	public function invoke($s)
	{
		$this->count++;
		return strtolower($s) . " ($this->count times)";
	}
}


function types()
{
	foreach (func_get_args() as $arg) {
		$res[] = gettype($arg);
	}

	return implode(', ', $res);
}


$latte = new Latte\Engine;
$latte->addFilter('nl2br', 'nl2br');
$latte->addFilter('h1', [new MyFilter, 'invoke']);
$latte->addFilter('h2', 'strtoupper');
$latte->addFilter('translate', function (FilterInfo $info, $s) { return strrev($s); });
$latte->addFilter('types', 'types');
$latte->addFilter(null, function ($name, $val) {
	return $name === 'dynamic' ? "<$name $val>" : null;
});
$latte->addFilter(null, function ($name, $val) {
	return $name === 'dynamic' ? "[$name $val]" : null;
});
$latte->addFilterLoader(function ($name) use ($latte) {
	if ($name === 'dynamic2') {
		return function ($val) {
			return "[$val]";
		};
	}
});


Assert::same('AA', $latte->invokeFilter('h2', ['aa']));
Assert::same('[dynamic aa]', $latte->invokeFilter('dynamic', ['aa']));
Assert::exception(function () use ($latte) {
	$latte->invokeFilter('unknown', ['']);
}, LogicException::class, "Filter 'unknown' is not defined.");

Assert::exception(function () use ($latte) {
	$latte->invokeFilter('h3', ['']);
}, LogicException::class, "Filter 'h3' is not defined, did you mean 'h1'?");


$params['hello'] = 'Hello World';
$params['date'] = strtotime('2008-01-02');

Assert::matchFile(
	__DIR__ . '/expected/filters.general.phtml',
	$latte->compile(__DIR__ . '/templates/filters.general.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/filters.general.html',
	$latte->renderToString(
		__DIR__ . '/templates/filters.general.latte',
		$params
	)
);
