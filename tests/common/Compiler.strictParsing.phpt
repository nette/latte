<?php declare(strict_types=1);

/**
 * Test: Compile errors in strict parsing
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->setStrictParsing();

Assert::exception(
	fn() => $latte->compile('{$this}'),
	Latte\CompileException::class,
	'Forbidden variable $this (on line 1 at column 2)',
);

Assert::exception(
	fn() => $latte->compile('<a>'),
	Latte\CompileException::class,
	'Unexpected end, expecting </a> for element started on line 1 at column 1 (on line 1 at column 4)',
);

Assert::exception(
	fn() => $latte->compile('</a>'),
	Latte\CompileException::class,
	"Unexpected '</' (on line 1 at column 1)",
);

Assert::exception(
	fn() => $latte->compile('<a></b>'),
	Latte\CompileException::class,
	"Unexpected '</b>', expecting </a> for element started on line 1 at column 1 (on line 1 at column 4)",
);

Assert::exception(
	fn() => $latte->compile('<a{if 1}{/if}>'),
	Latte\CompileException::class,
	'Only expression can be used as a HTML tag name (on line 1 at column 3)',
);

Assert::exception(
	fn() => $latte->compile('{contentType xml}<a></A>'),
	Latte\CompileException::class,
	"Unexpected '</A>', expecting </a> for element started on line 1 at column 18 (on line 1 at column 21)",
);
