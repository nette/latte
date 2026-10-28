<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\HtmlHelpers::convertHtmlToRcData
 */

use Latte\Runtime\HtmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('a &amp; <b>b</b>&lt;/textarea>', HtmlHelpers::convertHtmlToRcData('a &amp; <b>b</b></textarea>'));
Assert::same('&lt;/TITLE >&lt;/Textarea', HtmlHelpers::convertHtmlToRcData('</TITLE ></Textarea'));
Assert::same('<textarea></b> &copy a < b {{x}}', HtmlHelpers::convertHtmlToRcData('<textarea></b> &copy a < b {{x}}'));
Assert::same(str_repeat('&lt;/title', 10000), HtmlHelpers::convertHtmlToRcData(str_repeat('</title', 10000)));
