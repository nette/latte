<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\XmlHelpers::escapeAttr
 */

use Latte\Runtime\XmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', XmlHelpers::escapeAttr(null));
Assert::same('', XmlHelpers::escapeAttr(''));
Assert::same('1', XmlHelpers::escapeAttr(1));
Assert::same('string', XmlHelpers::escapeAttr('string'));
Assert::same('&lt; &amp; &apos; &quot; &gt;', XmlHelpers::escapeAttr('< & \' " >'));
Assert::same('&amp;quot;', XmlHelpers::escapeAttr('&quot;'));
Assert::same(' &quot;', XmlHelpers::escapeAttr(new Latte\Runtime\Html('<br> &quot;')));

Assert::same(
	"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\x09\x0a\u{FFFD}\u{FFFD}\x0d\u{FFFD}\u{FFFD}",
	XmlHelpers::escapeAttr("\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f"),
);
Assert::same(
	"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}",
	XmlHelpers::escapeAttr("\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f"),
);

// invalid UTF-8
Assert::same("foo \u{FFFD} bar", XmlHelpers::escapeAttr("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \u{FFFD}&quot; bar", XmlHelpers::escapeAttr("foo \xE3\x80\x22 bar")); // stripped UTF
