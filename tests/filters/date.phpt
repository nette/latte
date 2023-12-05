<?php

/**
 * Test: Latte\Essential\Filters::date()
 */

declare(strict_types=1);

use Latte\Essential\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('datatypes', function () {
	Assert::same("5.\u{a0}5.\u{a0}1978", Filters::date('1978-05-05'));
	Assert::same("23.\u{a0}1.\u{a0}1978", Filters::date(254_400_000));
	Assert::same("5.\u{a0}5.\u{a0}1978", Filters::date(new DateTime('1978-05-05')));
	Assert::same("5.\u{a0}5.\u{a0}1978", Filters::date(new DateTimeImmutable('1978-05-05')));
});


test('edge cases', function () {
	Assert::null(Filters::date(null));
	Assert::null(Filters::date(''));
});


test('timestamp & zone', function () {
	date_default_timezone_set('America/Los_Angeles');
	Assert::same('07:09', Filters::date(1_408_284_571, 'H:i'));
});


test('date/time formatting', function () {
	Assert::same('1212-09-26', Filters::date('1212-09-26', 'Y-m-d'));
});


test('interval', function () {
	Assert::same('30:10:10', Filters::date(new DateInterval('PT30H10M10S'), '%H:%I:%S'));
});
