<?php

/**
 * Test: Latte\Runtime\Filters::escapeXmlTag
 */

declare(strict_types=1);

use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('""', Filters::escapeXmlTag(null));
Assert::same('""', Filters::escapeXmlTag(''));
Assert::same('1', Filters::escapeXmlTag(1));
Assert::same('string', Filters::escapeXmlTag('string'));
Assert::same('N:string-string', Filters::escapeXmlTag('N:string-string'));
Assert::same('"&lt; &amp; &apos; &quot; &gt;"', Filters::escapeXmlTag('< & \' " >'));
Assert::same('"&lt;br&gt;"', Filters::escapeXmlTag(new Latte\Runtime\Html('<br>')));
Assert::same('"`hello"', Filters::escapeXmlTag('`hello'));
Assert::same(
	"\"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\x09\x0a\u{FFFD}\u{FFFD}\x0d\u{FFFD}\u{FFFD}\"",
	Filters::escapeXmlTag("\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f"),
);
Assert::same(
	"\"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\"",
	Filters::escapeXmlTag("\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f"),
);

// invalid UTF-8
Assert::same("\"foo \u{FFFD} bar\"", Filters::escapeXmlTag("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("\"foo \u{FFFD}&quot; bar\"", Filters::escapeXmlTag("foo \xE3\x80\x22 bar")); // stripped UTF
