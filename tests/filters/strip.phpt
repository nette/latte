<?php

/**
 * Test: Latte\Essential\Filters::strip()
 */

declare(strict_types=1);

use Latte\Engine;
use Latte\Essential\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_TEXT);
	Assert::same('', Filters::strip($info, ''));
	Assert::same('', Filters::strip($info, "\r\n "));
	Assert::same('A B', Filters::strip($info, "A\r\t\n  B"));
	Assert::same('<p> Hello </p>', Filters::strip($info, "<p> Hello </p>\r\n "));
	Assert::same('<pre> </pre>', Filters::strip($info, "<pre>  \r\n </pre>\r\n "));
});


test('', function () {
	$info = new FilterInfo(Engine::CONTENT_HTML);
	Assert::same('', Filters::strip($info, ''));
	Assert::same('', Filters::strip($info, "\r\n "));
	Assert::same('A B', Filters::strip($info, "A\r\t\n  B"));
	Assert::same('<p> Hello </p>', Filters::strip($info, "<p> Hello </p>\r\n "));
	Assert::same("<pre>  \r\n </pre>", Filters::strip($info, "<pre>  \r\n </pre>\r\n "));
});
