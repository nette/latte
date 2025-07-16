<?php

/**
 * Test: Latte\Runtime\FilterExecutor
 */

declare(strict_types=1);

use Latte\ContentType;
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


test('filter registration and invocation', function () {
	$filters = new FilterExecutor;

	$filters->add('f1', 'strtoupper');
	Assert::same('strtoupper', $filters->f1);
	Assert::same('AA', ($filters->f1)('aa'));

	Assert::exception(
		fn() => ($filters->F1)(''),
		LogicException::class,
		"Filter 'F1' is not defined, did you mean 'f1'?",
	);

	$filters->add('f1', 'trim');
	Assert::same('trim', $filters->f1);

	$filters->add('f2', [new MyFilter, 'invoke']);
	Assert::same('aa', ($filters->f2)('aA'));

	$filters->add('f3', 'MyFilter::invoke');
	Assert::same('aa', ($filters->f3)('aA'));

	$filters->add('f4', new MyFilter);
	Assert::same('AA', ($filters->f4)('aA'));
});


test('dynamic filter resolution', function () {
	$filters = new FilterExecutor;
	$filters->add(null, function ($name) use ($filters) {
		if ($name === 'dynamic') {
			return fn(...$vals) => $name . ',' . implode(',', $vals);
		}
	});
	Assert::same('dynamic,1,2', ($filters->dynamic)(1, 2));
	Assert::same('dynamic,1,3', ($filters->dynamic)(1, 3));

	Assert::exception(
		fn() => ($filters->unknown)(''),
		LogicException::class,
		"Filter 'unknown' is not defined.",
	);
});


test('dynamic filters with content type awareness', function () {
	$filters = new FilterExecutor;
	$filters->add(null, function ($name) use ($filters) {
		if ($name === 'dynamic') {
			return fn(FilterInfo $info, ...$vals) => $name . ',' . implode(',', $vals);
		}
	});
	Assert::same('dynamic,x,y', $filters->filterContent('dynamic', new FilterInfo, 'x', 'y'));

	Assert::exception(
		fn() => $filters->filterContent('unknown', new FilterInfo, ''),
		LogicException::class,
		"Filter 'unknown' is not defined.",
	);
});


test('content type handling in filters', function () {
	$filters = new FilterExecutor;

	// FilterInfo aware called as classic
	$filters->add('f1', fn(FilterInfo $info, $val) => gettype($info->contentType) . ',' . strtolower($val), true);

	Assert::same('NULL,aa', ($filters->f1)('aA'));
	Assert::same('NULL,aa', ($filters->f1)('aA'));


	// classic called as FilterInfo aware
	$filters->add('f2', fn($val) => strtolower($val));
	Assert::exception(
		fn() => $filters->filterContent('f2', new FilterInfo(ContentType::Html), 'aA<b>'),
		Latte\RuntimeException::class,
		'Filter |f2 is called with incompatible content type HTML, try to prepend |stripHtml.',
	);


	// FilterInfo aware called as FilterInfo aware
	$filters->add('f3', function (FilterInfo $info, $val) {
		$type = $info->contentType;
		$info->contentType = ContentType::Css;
		return $type . ',' . strtolower($val);
	}, true);

	$info = new FilterInfo(ContentType::Html);
	Assert::same('html,aa', $filters->filterContent('f3', $info, 'aA'));
	Assert::same(ContentType::Css, $info->contentType);
});


test('HTML object handling in filters', function () {
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
	Assert::error(
		fn() => $filters->filterContent('f5', new FilterInfo(ContentType::Text), 'aA'),
		E_USER_NOTICE,
		'Filter |f5 should be changed to content-aware filter.',
	);

	$info = new FilterInfo(ContentType::Text);
	Assert::same('aa', @$filters->filterContent('f5', $info, 'aA')); // @ ignore E_USER_NOTICE
	Assert::same(ContentType::Html, $info->contentType);
});
