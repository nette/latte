<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\Filters::escapeXmlText
 */

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Filters::escapeXmlText(null));
Assert::same('', Filters::escapeXmlText(''));
Assert::same('1', Filters::escapeXmlText(1));
Assert::same('string', Filters::escapeXmlText('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', Filters::escapeXmlText('< & \' " >'));
Assert::same('<br>', Filters::escapeXmlText(new Latte\Runtime\Html('<br>')));
Assert::same(
	"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\x09\x0a\u{FFFD}\u{FFFD}\x0d\u{FFFD}\u{FFFD}",
	Filters::escapeXmlText("\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f"),
);
Assert::same(
	"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}",
	Filters::escapeXmlText("\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f"),
);

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", Filters::escapeXmlText("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", Filters::escapeXmlText("foo \xE3\x80\x22 bar")); // stripped UTF
