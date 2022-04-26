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
$latte->addFilter('translate', fn(FilterInfo $info, $s) => strrev($s));
$latte->addFilter('types', 'types');
$latte->addFilter(null, fn($name, $val) => $name === 'dynamic' ? "<$name $val>" : null);
$latte->addFilter(null, fn($name, $val) => $name === 'dynamic' ? "[$name $val]" : null);
$latte->addFilterLoader(function ($name) use ($latte) {
	if ($name === 'dynamic2') {
		return fn($val) => "[$val]";
	}
});


Assert::same('AA', $latte->invokeFilter('h2', ['aa']));
Assert::same('[dynamic aa]', $latte->invokeFilter('dynamic', ['aa']));
Assert::exception(
	fn() => $latte->invokeFilter('unknown', ['']),
	LogicException::class,
	"Filter 'unknown' is not defined.",
);

Assert::exception(
	fn() => $latte->invokeFilter('h3', ['']),
	LogicException::class,
	"Filter 'h3' is not defined, did you mean 'h1'?",
);


$params['hello'] = 'Hello World';
$params['date'] = strtotime('2008-01-02');

Assert::matchFile(
	__DIR__ . '/expected/general.phtml',
	$latte->compile(__DIR__ . '/templates/general.latte'),
);
Assert::matchFile(
	__DIR__ . '/expected/general.html',
	$latte->renderToString(
		__DIR__ . '/templates/general.latte',
		$params,
	),
);
