<?php

// Escape sequences in double-quoted strings

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<XX
	"\\n\\r\\t\\f\\v\\$\\"\\\\",
	"\x0\x1\x2\x3\x4\x5\x6\x7\x8\x9\xa\xb\xc\xd\xe\xf\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f",
	"\\0000\\0001",
	"\\u{A0}",
	"äöü",
	"\\xc0\\x80",
	"\\xd0\\x01",
	"\\xf0\\x80\\x80",

	<<<DOC
	\\n\\r\\t\\f\\v\\$\\"\\\\
	\x0\x1\x2\x3\x4\x5\x6\x7\x8\x9\xa\xb\xc\xd\xe\xf\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f
	\\0000\\0001
	\\u{A0}
	äöü
	DOC,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	normalizeNl(file_get_contents(__DIR__ . '/expected/stringEscaping.dat')),
	$code,
);
