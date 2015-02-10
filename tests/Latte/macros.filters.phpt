<?php

/**
 * Test: Latte\Engine: filters test.
 */

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
	foreach (func_get_args() as $arg) $res[] = gettype($arg);
	return implode(', ', $res);
}


$latte = new Latte\Engine;
$latte->addFilter('nl2br', 'nl2br');
$latte->addFilter('h1', array(new MyFilter, 'invoke'));
$latte->addFilter('h2', 'strtoupper');
$latte->addFilter('translate', 'strrev');
$latte->addFilter('types', 'types');
$latte->addFilter(NULL, function($name, array $args) {
	return $name === 'dynamic' ? "<$name $args[0]>" : NULL;
});
$latte->addFilter(NULL, function($name, array $args) {
	return $name === 'dynamic' ? "[$name $args[0]]" : NULL;
});
$latte->addFilter(NULL, function($name, array $args) use ($latte) {
	if ($name === 'dynamic2') {
		$latte->addFilter($name, function($val) {
			return "[$val]";
		});
	}
});


Assert::same('AA', $latte->invokeFilter('h2', array('aa')));
Assert::same('[dynamic aa]', $latte->invokeFilter('dynamic', array('aa')));
Assert::exception(function() use ($latte) {
	$latte->invokeFilter('unknown', array(''));
}, 'LogicException', "Filter 'unknown' is not defined.");


$params['hello'] = 'Hello World';
$params['date'] = strtotime('2008-01-02');

Assert::matchFile(
	__DIR__ . '/expected/macros.filters.phtml',
	$latte->compile(__DIR__ . '/templates/filters.latte')
);
Assert::matchFile(
	__DIR__ . '/expected/macros.filters.html',
	$latte->renderToString(
		__DIR__ . '/templates/filters.latte',
		$params
	)
);
