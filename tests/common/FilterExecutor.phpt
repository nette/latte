<?php

/**
 * Test: Latte\Runtime\FilterExecutor
 */

declare(strict_types=1);

use Latte\Runtime\FilterExecutor;
use Latte\Runtime\FilterInfo;
use Latte\Runtime\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class MyFilter
{
	public static function invoke($s)
	{
		return strtolower($s);
	}


	public function __invoke($s)
	{
		return strtoupper($s);
	}
}


test('', function () {
	$filters = new FilterExecutor;

	$filters->add('f1', 'strtoupper');
	Assert::same('strtoupper', $filters->f1);
	Assert::same('AA', ($filters->f1)('aa'));

	Assert::exception(function () use ($filters) {
		($filters->F1)('');
	}, LogicException::class, "Filter 'F1' is not defined, did you mean 'f1'?");

	$filters->add('f1', 'trim');
	Assert::same('trim', $filters->f1);

	$filters->add('f2', [new MyFilter, 'invoke']);
	Assert::same('aa', ($filters->f2)('aA'));

	$filters->add('f3', 'MyFilter::invoke');
	Assert::same('aa', ($filters->f3)('aA'));

	$filters->add('f4', new MyFilter);
	Assert::same('AA', ($filters->f4)('aA'));
});


test('', function () {
	$filters = new FilterExecutor;
	$filters->add(null, function ($name) use ($filters) {
		if ($name === 'dynamic') {
			return fn(...$vals) => $name . ',' . implode(',', $vals);
		}
	});
	Assert::same('dynamic,1,2', ($filters->dynamic)(1, 2));
	Assert::same('dynamic,1,3', ($filters->dynamic)(1, 3));

	Assert::exception(function () use ($filters) {
		($filters->unknown)('');
	}, LogicException::class, "Filter 'unknown' is not defined.");
});


test('', function () {
	$filters = new FilterExecutor;

	// FilterInfo aware called as classic
	$filters->add('f1', fn(FilterInfo $info, $val) => gettype($info->contentType) . ',' . strtolower($val), true);

	Assert::same('NULL,aa', ($filters->f1)('aA'));
	Assert::same('NULL,aa', ($filters->f1)('aA'));


	// classic called as FilterInfo aware
	$filters->add('f2', fn($val) => strtolower($val));
	Assert::exception(function () use ($filters) {
		$filters->filterContent('f2', new FilterInfo('html'), 'aA<b>');
	}, Latte\RuntimeException::class, 'Filter |f2 is called with incompatible content type HTML, try to prepend |stripHtml.');


	// FilterInfo aware called as FilterInfo aware
	$filters->add('f3', function (FilterInfo $info, $val) {
		$type = $info->contentType;
		$info->contentType = 'new';
		return $type . ',' . strtolower($val);
	}, true);

	$info = new FilterInfo('html');
	Assert::same('html,aa', $filters->filterContent('f3', $info, 'aA'));
	Assert::same('new', $info->contentType);
});


test('', function () {
	$filters = new FilterExecutor;

	// FilterInfo aware called as classic with Latte\Runtime\Html
	$filters->add('f4', function (FilterInfo $info, $val, $newType) {
		$type = $info->contentType;
		$info->contentType = $newType;
		return $type . ',' . gettype($val) . ',' . strtolower($val);
	}, true);

	Assert::equal(new Html('html,string,aa'), ($filters->f4)(new Html('aA'), 'html'));
	Assert::equal('html,string,aa', ($filters->f4)(new Html('aA'), 'text'));
	Assert::equal('html,string,aa', ($filters->f4)(new Html('aA'), null));


	// classic called as FilterInfo aware with Latte\Runtime\Html
	$filters->add('f5', fn($val) => new Html(strtolower($val)));
	Assert::error(function () use ($filters) {
		$filters->filterContent('f5', new FilterInfo('text'), 'aA');
	}, E_USER_NOTICE, 'Filter |f5 should be changed to content-aware filter.');

	$info = new FilterInfo('text');
	Assert::same('aa', @$filters->filterContent('f5', $info, 'aA')); // @ ignore E_USER_NOTICE
	Assert::same('html', $info->contentType);
});
