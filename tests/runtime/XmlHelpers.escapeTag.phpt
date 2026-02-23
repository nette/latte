<?php declare(strict_types=1);

/**
 * Test: Latte\Runtime\XmlHelpers::escapeTag
 */

use Latte\Runtime\XmlHelpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', XmlHelpers::escapeTag(null));
Assert::same('', XmlHelpers::escapeTag(''));
Assert::same('1', XmlHelpers::escapeTag(1));
Assert::same('string', XmlHelpers::escapeTag('string'));
Assert::same('žluťoučký', XmlHelpers::escapeTag('žluťoučký'));
Assert::same('&lt;&#32;&amp;&#32;&apos;&#32;&quot;&#32;&#61;&#32;&#47;&#32;&gt;', XmlHelpers::escapeTag('< & \' " = / >'));
Assert::same('&amp;quot;', XmlHelpers::escapeTag('&quot;'));
Assert::same('&lt;br&gt;', XmlHelpers::escapeTag(new Latte\Runtime\Html('<br>')));

// control characters
Assert::same(
	"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}&#9;&#10;\u{FFFD}\u{FFFD}&#13;\u{FFFD}\u{FFFD}",
	XmlHelpers::escapeTag("\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f"),
);
Assert::same(
	"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}",
	XmlHelpers::escapeTag("\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f"),
);

// invalid UTF-8
Assert::same("foo&#32;\u{FFFD}&#32;bar", XmlHelpers::escapeTag("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo&#32;\u{FFFD}&quot;&#32;bar", XmlHelpers::escapeTag("foo \xE3\x80\x22 bar")); // stripped UTF
