<?php

/**
 * Test: Latte\Runtime\FilterExecutor
 */

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

	function __invoke($s)
	{
		return strtoupper($s);
	}
}


test(function () {
	$filters = new FilterExecutor;

	Assert::true(count($filters->getAll()) > 28);
	Assert::same('upper', $filters->getAll()['upper']);

	$filters->add('f1', 'strtoupper');
	Assert::same('strtoupper', $filters->f1);
	Assert::same('AA', call_user_func($filters->f1, 'aa'));

	$filters->add('f1', 'trim');
	Assert::same('trim', $filters->f1);

	$filters->add('f2', [new MyFilter, 'invoke']);
	Assert::same('aa', call_user_func($filters->f2, 'aA'));

	$filters->add('f3', 'MyFilter::invoke');
	Assert::same('aa', call_user_func($filters->f3, 'aA'));

	$filters->add('f4', new MyFilter);
	Assert::same('AA', call_user_func($filters->f4, 'aA'));

	Assert::exception(function () use ($filters) {
		call_user_func($filters->h3, '');
	}, 'LogicException', "Filter 'h3' is not defined, did you mean 'f3'?");
});


test(function () {
	$filters = new FilterExecutor;

	$filters->add('camelCase', 'strtolower');
	Assert::same('strtolower', $filters->camelCase);

	Assert::exception(function () use ($filters) {
		call_user_func($filters->camelcase, '');
	}, 'LogicException', "Filters are case-sensitive. Call 'camelCase' instead of 'camelcase'.");

	$filters2 = new FilterExecutor;

	$filters2->add(NULL, function ($name, $val) {
		return $name . ',' . $val;
	});
	Assert::same('myFilter,1', call_user_func($filters2->myFilter, 1));
});


test(function () {
	$filters = new FilterExecutor;
	$filters->add(NULL, function ($name, $val) {
		return implode(',', func_get_args());
	});
	Assert::same('dynamic,1,2', call_user_func($filters->dynamic, 1, 2));
	Assert::same('dynamic,1,2', call_user_func($filters->dynamic, 1, 2));
	Assert::same('another,1,2', call_user_func($filters->another, 1, 2));

	$filters2 = new FilterExecutor;
	$filters2->add(NULL, function ($name, $val) {
		return 'different';
	});
	Assert::same('different', call_user_func($filters2->dynamic, 1, 2));
});


test(function () {
	$filters = new FilterExecutor;

	Assert::error(function () use ($filters) {
		call_user_func($filters->striphtml, '');
	}, E_USER_DEPRECATED, "Macro striphtml is deprecated. Use its camelCase version.");
});


test(function () {
	$filters = new FilterExecutor;
	$filters->add(NULL, function ($name, $val) use ($filters) {
		if ($name === 'dynamic') {
			$filters->add($name, function ($val) {
				return implode(',', func_get_args());
			});
		}
	});
	Assert::same('1,2', call_user_func($filters->dynamic, 1, 2));
	Assert::same('1,2', call_user_func($filters->dynamic, 1, 2));

	Assert::exception(function () use ($filters) {
		call_user_func($filters->unknown, '');
	}, 'LogicException', "Filter 'unknown' is not defined.");
});


test(function () {
	$filters = new FilterExecutor;

	// FilterInfo aware called as classic
	$filters->add('f1', function (FilterInfo $info, $val) {
		return gettype($info->contentType) . ',' . strtolower($val);
	}, TRUE);

	Assert::same('NULL,aa', call_user_func($filters->f1, 'aA'));
	Assert::same('NULL,aa', call_user_func($filters->f1, 'aA'));


	// classic called as FilterInfo aware
	$filters->add('f2', function ($val) {
		return strtolower($val);
	});
	Assert::error(function () use ($filters) {
		$filters->filterContent('f2', new FilterInfo('html'), 'aA<b>');
	}, E_USER_WARNING, 'Filter |f2 is called with incompatible content type HTML, try to prepend |stripHtml.');


	// FilterInfo aware called as FilterInfo aware
	$filters->add('f3', function (FilterInfo $info, $val) {
		$type = $info->contentType; $info->contentType = 'new';
		return $type . ',' . strtolower($val);
	}, TRUE);

	$info = new FilterInfo('html');
	Assert::same('html,aa', $filters->filterContent('f3', $info, 'aA'));
	Assert::same('new', $info->contentType);
});


test(function () {
	$filters = new FilterExecutor;

	// FilterInfo aware called as classic with Latte\Runtime\Html
	$filters->add('f4', function (FilterInfo $info, $val, $newType) {
		$type = $info->contentType;
		$info->contentType = $newType;
		return $type . ',' . gettype($val) . ',' . strtolower($val);
	}, TRUE);

	Assert::equal(new Html('html,string,aa'), call_user_func($filters->f4, new Html('aA'), 'html'));
	Assert::equal('html,string,aa', call_user_func($filters->f4, new Html('aA'), 'text'));
	Assert::equal('html,string,aa', call_user_func($filters->f4, new Html('aA'), NULL));


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
