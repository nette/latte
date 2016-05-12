<?php

/**
 * Test: Latte\Engine: filters test.
 */

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
$latte->addFilter(NULL, function ($name, $val) {
	return $name === 'dynamic' ? "<$name $val>" : NULL;
});
$latte->addFilter(NULL, function ($name, $val) {
	return $name === 'dynamic' ? "[$name $val]" : NULL;
});
$latte->addFilter(NULL, function ($name, $val) use ($latte) {
	if ($name === 'dynamic2') {
		$latte->addFilter($name, function ($val) {
			return "[$val]";
		});
	}
});


Assert::same('AA', $latte->invokeFilter('h2', ['aa']));
Assert::same('[dynamic aa]', $latte->invokeFilter('dynamic', ['aa']));
Assert::exception(function () use ($latte) {
	$latte->invokeFilter('unknown', ['']);
}, 'LogicException', "Filter 'unknown' is not defined.");

Assert::exception(function () use ($latte) {
	$latte->invokeFilter('h3', ['']);
}, 'LogicException', "Filter 'h3' is not defined, did you mean 'h1'?");


$params['hello'] = 'Hello World';
$params['date'] = strtotime('2008-01-02');

Assert::matchFile(
	__DIR__ . '/expected/filters.general.phtml',
	$latte->compile(__DIR__ . '/templates/filters.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/filters.general.html',
	$latte->renderToString(
		__DIR__ . '/templates/filters.latte',
		$params
	)
);
