<?php

/**
 * Test: Latte\Essential\Filters::date()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('no locale', function () {
	$filters = new Filters;

	Assert::null($filters->date(null));
	Assert::same("5.\u{a0}5.\u{a0}1978", $filters->date('1978-05-05'));
	Assert::same("5.\u{a0}5.\u{a0}1978", $filters->date(new DateTime('1978-05-05')));
	Assert::same('1978-01-23', $filters->date(254_400_000, 'Y-m-d'));
	Assert::same('1212-09-26', $filters->date('1212-09-26', 'Y-m-d'));
	Assert::same('1212-09-26', $filters->date(new DateTimeImmutable('1212-09-26'), 'Y-m-d'));

	// timestamp
	date_default_timezone_set('America/Los_Angeles');
	Assert::same("23.\u{a0}1.\u{a0}1978", $filters->date(254_400_000));
	Assert::same('07:09', $filters->date(1_408_284_571, 'H:i'));
});


test('date interval', function () {
	$filters = new Filters;

	Assert::same('30:10:10', $filters->date(new DateInterval('PT30H10M10S'), '%H:%I:%S'));
});


test('local date/time', function () {
	$filters = new Filters;
	$filters->locale = 'cs_CZ';

	// date format
	Assert::null($filters->date(null, 'medium'));
	Assert::same("5.\u{a0}5.\u{a0}1978", $filters->date('1978-05-05', 'medium'));
	Assert::same('05.05.78', $filters->date(new DateTime('1978-05-05'), 'short'));
	Assert::same("5.\u{a0}5.\u{a0}1978", $filters->date(new DateTime('1978-05-05'), 'medium'));
	Assert::same("5.\u{a0}května 1978", $filters->date(new DateTime('1978-05-05'), 'long'));
	Assert::same("pátek 5.\u{a0}května 1978", $filters->date(new DateTime('1978-05-05'), 'full'));

	// time format
	Assert::same('12:13', $filters->date(new DateTime('12:13:14'), 'time'));
	Assert::same('12:13:14', $filters->date(new DateTime('12:13:14'), 'time+sec'));

	// combined
	Assert::same('05.05.78 12:13', $filters->date(new DateTime('1978-05-05 12:13:14'), 'short+time'));
	Assert::same('05.05.78 12:13:14', $filters->date(new DateTime('1978-05-05 12:13:14'), 'short+time+sec'));
});
