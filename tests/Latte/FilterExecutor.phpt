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
	Assert::same('strtoupper', $filters->F1);
	Assert::same('AA', ($filters->f1)('aa'));

	$filters->add('f1', 'trim');
	Assert::same('trim', $filters->f1);
	Assert::same('trim', $filters->F1);

	$filters->add('f2', [new MyFilter, 'invoke']);
	Assert::same('aa', ($filters->f2)('aA'));

	$filters->add('f3', 'MyFilter::invoke');
	Assert::same('aa', ($filters->f3)('aA'));

	$filters->add('f4', new MyFilter);
	Assert::same('AA', ($filters->f4)('aA'));

	Assert::exception(function () use ($filters) {
		($filters->h3)('');
	}, LogicException::class, "Filter 'h3' is not defined, did you mean 'f3'?");
});


test('', function () {
	$filters = new FilterExecutor;
	$filters->add(null, function ($name, $val) {
		return implode(',', func_get_args());
	});
	Assert::same('dynamic,1,2', ($filters->dynamic)(1, 2));
	Assert::same('dynamic,1,2', ($filters->dynamic)(1, 2));
	Assert::same('dynamic,1,2', ($filters->Dynamic)(1, 2));
	Assert::same('another,1,2', ($filters->another)(1, 2));

	$filters2 = new FilterExecutor;
	$filters2->add(null, function ($name, $val) {
		return 'different';
	});
	Assert::same('different', ($filters2->dynamic)(1, 2));
});


test('', function () {
	$filters = new FilterExecutor;
	$filters->add(null, function ($name, $val) use ($filters) {
		if ($name === 'dynamic') {
			$filters->add($name, function ($val) {
				return implode(',', func_get_args());
			});
		}
	});
	Assert::same('1,2', ($filters->dynamic)(1, 2));
	Assert::same('1,2', ($filters->dynamic)(1, 2));

	Assert::exception(function () use ($filters) {
		($filters->unknown)('');
	}, LogicException::class, "Filter 'unknown' is not defined.");
});


test('', function () {
	$filters = new FilterExecutor;

	// FilterInfo aware called as classic
	$filters->add('f1', function (FilterInfo $info, $val) {
		return gettype($info->contentType) . ',' . strtolower($val);
	}, true);

	Assert::same('NULL,aa', ($filters->f1)('aA'));
	Assert::same('NULL,aa', ($filters->f1)('aA'));


	// classic called as FilterInfo aware
	$filters->add('f2', function ($val) {
		return strtolower($val);
	});
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
	$filters->add('f5', function ($val) {
		return new Html(strtolower($val));
	});
	Assert::error(function () use ($filters) {
		$filters->filterContent('f5', new FilterInfo('text'), 'aA');
	}, E_USER_NOTICE, 'Filter |f5 should be changed to content-aware filter.');

	$info = new FilterInfo('text');
	Assert::same('aa', @$filters->filterContent('f5', $info, 'aA')); // @ ignore E_USER_NOTICE
	Assert::same('html', $info->contentType);
});
