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

// a trailing start of the end tag
Assert::same('a&lt;', HtmlHelpers::convertHtmlToRcData('a<'));
Assert::same('a&lt;/', HtmlHelpers::convertHtmlToRcData('a</'));
Assert::same('a&lt;/TEXTARE', HtmlHelpers::convertHtmlToRcData('a</TEXTARE'));
Assert::same('a&lt;/titl', HtmlHelpers::convertHtmlToRcData('a</titl'));
Assert::same('a</b', HtmlHelpers::convertHtmlToRcData('a</b'));
Assert::same("a<\n", HtmlHelpers::convertHtmlToRcData("a<\n"));
Assert::same(str_repeat('</textare', 9999) . '&lt;/textare', HtmlHelpers::convertHtmlToRcData(str_repeat('</textare', 10000)));
Assert::same(str_repeat('<', 9999) . '&lt;', HtmlHelpers::convertHtmlToRcData(str_repeat('<', 10000)));
