<?php declare(strict_types=1);

/**
 * Test: Latte\Essential\Filters::spaceless()
 */

use Latte\ContentType;
use Latte\Essential\Filters;
use Latte\Runtime\FilterInfo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// minification itself is covered by WhitespaceMinifier.phpt, here we test the content type dispatch
test('content type dispatch', function () {
	// null defaults to text mode
	Assert::same("A\nB", Filters::spaceless(new FilterInfo, "A\r\t\n  B"));
	// text mode keeps newlines
	Assert::same("Ahoj světe\n\ndalší", Filters::spaceless(new FilterInfo(ContentType::Text), "Ahoj   světe  \n   \n  další"));
	// HTML mode understands markup
	Assert::same('<p>Hello <i>aa</i></p>', Filters::spaceless(new FilterInfo(ContentType::Html), "<p> Hello <i> aa </i> </p>\r\n "));
	// XML treats all tags as whitespace-insensitive
	Assert::same('<a><b>x</b></a>', Filters::spaceless(new FilterInfo(ContentType::Xml), " <a>\n <b> x </b>\n</a> "));
	// attribute subcontext collapses newlines to a single space
	Assert::same('a b', Filters::spaceless(new FilterInfo('html/attr'), "a \n\n b"));
});
