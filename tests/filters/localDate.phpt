<?php

/**
 * Test: Latte\Essential\Filters::localDate()
 * @phpVersion 8.1
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('datatypes', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	Assert::same("5.\u{a0}května 1978", $filters->localDate('1978-05-05'));
	Assert::same("23.\u{a0}ledna 1978", $filters->localDate(254_400_000));
	Assert::same("5.\u{a0}května 1978", $filters->localDate(new DateTime('1978-05-05')));
	Assert::same("5.\u{a0}května 1978", $filters->localDate(new DateTimeImmutable('1978-05-05')));
});


test('edge cases', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	Assert::null($filters->localDate(null));
	Assert::null($filters->localDate(''));
	Assert::exception(
		fn() => $filters->localDate('2024-02-31'),
		InvalidArgumentException::class,
		'The parsed date was invalid',
	);
});


test('PHP timezone', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	date_default_timezone_set('Europe/Prague');
	$now = new DateTime('2024-10-01 21:37:45 UTC');
	Assert::same('23:37:45 SELČ', $filters->localDate($now, 'Hmmssz'));
	Assert::same('23:37', $filters->localDate($now, time: 'short'));
});


test('timestamp & timezone', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	date_default_timezone_set('America/Los_Angeles');
	Assert::same('7:09', $filters->localDate(1_408_284_571, 'jm'));
});


test('skeleton pattern', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	Assert::same('květen', $filters->localDate('1978-05-05', 'MMMM'));
	Assert::same('5. května', $filters->localDate('1978-05-05', 'd MMMM'));
	Assert::same('květen 78', $filters->localDate('1978-05-05', 'MMMM yy'));
});


test('full/long/medium/short', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	// date format
	Assert::same('05.05.78', $filters->localDate('1978-05-05', date: 'short'));
	Assert::same("5.\u{a0}5.\u{a0}1978", $filters->localDate('1978-05-05', date: 'medium'));
	Assert::same("5.\u{a0}května 1978", $filters->localDate('1978-05-05', date: 'long'));
	Assert::same("pátek 5.\u{a0}května 1978", $filters->localDate('1978-05-05', date: 'full'));

	// time format
	Assert::same('12:13', $filters->localDate('12:13:14', time: 'short'));
	Assert::same('12:13:14', $filters->localDate('12:13:14', time: 'medium'));
	Assert::same('12:13:14 PDT', $filters->localDate('12:13:14', time: 'long'));
	Assert::match('12:13:14%a%', $filters->localDate('12:13:14', time: 'full'));

	// combined
	$filters->locale = 'en_US';
	Assert::match('5/5/78, 12:13%a%PM', $filters->localDate('1978-05-05 12:13:14', date: 'short', time: 'short'));
});


test('relative full/long/medium/short', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	Assert::same('05.05.78', $filters->localDate('1978-05-05', date: 'relative-short'));
	Assert::same("5.\u{a0}5.\u{a0}1978", $filters->localDate('1978-05-05', date: 'relative-medium'));
	Assert::same("5.\u{a0}května 1978", $filters->localDate('1978-05-05', date: 'relative-long'));
	Assert::same("pátek 5.\u{a0}května 1978", $filters->localDate('1978-05-05', date: 'relative-full'));

	$now = new DateTime;
	Assert::same('dnes', $filters->localDate($now, date: 'relative-short'));
	Assert::same('dnes', $filters->localDate($now, date: 'relative-medium'));
	Assert::same('dnes', $filters->localDate($now, date: 'relative-long'));
	Assert::same('dnes', $filters->localDate($now, date: 'relative-full'));
});
