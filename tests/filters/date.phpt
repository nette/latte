<?php

/**
 * Test: Latte\Runtime\Filters::date()
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


setlocale(LC_TIME, 'C');


Assert::null(Filters::date(null));


Assert::same("23.\u{a0}1.\u{a0}1978", Filters::date(254400000));


Assert::same("5.\u{a0}5.\u{a0}1978", Filters::date('1978-05-05'));


Assert::same("5.\u{a0}5.\u{a0}1978", Filters::date(new DateTime('1978-05-05')));


Assert::same('1978-01-23', Filters::date(254400000, 'Y-m-d'));


Assert::same('1212-09-26', Filters::date('1212-09-26', 'Y-m-d'));


Assert::same('1212-09-26', Filters::date(new DateTimeImmutable('1212-09-26'), 'Y-m-d'));


Assert::same('30:10:10', Filters::date(new DateInterval('PT30H10M10S'), '%H:%I:%S'));


date_default_timezone_set('America/Los_Angeles');
Assert::same('07:09', Filters::date(1408284571, 'H:i'));
