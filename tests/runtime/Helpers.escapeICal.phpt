<?php

/**
 * Test: Latte\Runtime\Helpers::escapeICal
 */

declare(strict_types=1);

use Latte\Runtime\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', Helpers::escapeICal(null));
Assert::same('', Helpers::escapeICal(''));
Assert::same('1', Helpers::escapeICal(1));
Assert::same('string', Helpers::escapeICal('string'));
Assert::same('\"\;\\\\\,\:', Helpers::escapeICal('";\,:'));
Assert::same('<br>', Helpers::escapeICal(new Latte\Runtime\Html('<br>')));
Assert::same(
	"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\x09\\n\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}",
	Helpers::escapeICal("\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f"),
);
Assert::same(
	"\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}\u{FFFD}",
	Helpers::escapeICal("\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f"),
);

// invalid UTF-8
Assert::same("foo \u{D800} bar", Helpers::escapeICal("foo \u{D800} bar")); // invalid codepoint high surrogates
Assert::same("foo \xE3\x80\\\x22 bar", Helpers::escapeICal("foo \xE3\x80\x22 bar")); // stripped UTF
