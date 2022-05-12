<?php

// Literals

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* not actually literals, but close */
	null,
	true,
	false,
	NULL,
	TRUE,
	FALSE,

	/* integers (normalized to decimal) */
	0,
	11,
	011,
	0x11,
	0b11,

	/* floats (normalized to ... something) */
	0.,
	.0,
	0.0,
	0e1000,
	1.0,
	1e100,
	1e1000,
	1E-100,
	1000000000000000000000000000000000000000000000000000000000000000000000000000000000000,
	378282246310005.0,
	10000000000000002.0,

	/* strings (single quoted) */
	'a',
	'a
	b',
	'a\'b',
	'a\b',
	'a\\',

	/* strings (double quoted) */
	"a",
	"a\nb",
	"a'b",
	"a\b",
	"$a",
	"a$b",
	"$a$b",
	"$a $b",
	"a{$b}c",
	"a$a[b]c",
	"\{$A}",
	"\{ $A }",
	"\\{$A}",
	"\\{ $A }",
	"$$A[B]",

	/* make sure indentation doesn't mess anything up */
	function ()
	{
	    return ["a\nb",
	    'a
	b',
	    "a\n\tb"];
	}
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
null,
true,
false,
null,
true,
false,
0,
11,
011,
0x11,
0b11,
0.0,
0.0,
0.0,
0.0,
1.0,
1.0E+100,
\INF,
1.0E-100,
1.0E+84,
378282246310005.0,
10000000000000002.0,
'a',
'a
b',
'a\'b',
'a\\b',
'a\\',
'a',
'a
b',
'a\'b',
'a\\b',
"{$a}",
"a{$b}",
"{$a}{$b}",
"{$a} {$b}",
"a{$b}c",
"a{$a['b']}c",
"\\{{$A}}",
"\\{ {$A} }",
"\\{$A}",
"\\{ {$A} }",
"\${$A['B']}",
fn() => ['a
b', 'a
b', 'a
	b']
