<?php

/**
 * Test: unknown macro.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{unknown}'),
	Latte\CompileException::class,
	'Unknown tag {unknown}',
);

Assert::exception(
	fn() => $latte->compile('{class}'),
	Latte\CompileException::class,
	'Unknown tag {class}',
);

Assert::exception(
	fn() => $latte->compile('{forech}'),
	Latte\CompileException::class,
	'Unknown tag {forech}, did you mean {foreach}?',
);

Assert::exception(
	fn() => $latte->compile('<p n:forech>'),
	'Latte\CompileException',
	'Unknown attribute n:forech, did you mean n:foreach?',
);

Assert::exception(
	fn() => $latte->compile('<style>body {color:blue}</style>'),
	Latte\CompileException::class,
	'Unknown tag {color:blue} (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)',
);

Assert::exception(
	fn() => $latte->compile('<script>if (true) {return}</script>'),
	Latte\CompileException::class,
	'Unknown tag {return} (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)',
);

Assert::exception(
	fn() => $latte->compile('<ul n:abc></ul>'),
	Latte\CompileException::class,
	'Unknown attribute n:abc',
);

Assert::exception(
	fn() => $latte->compile('<ul n:abc n:klm></ul>'),
	Latte\CompileException::class,
	'Unknown attribute n:abc and n:klm',
);

Assert::exception(
	fn() => $latte->compile('<a n:tag-class=$cond>'),
	Latte\CompileException::class,
	'Unknown attribute n:tag-class',
);

Assert::exception(
	fn() => $latte->compile('<a n:inner-class=$cond>'),
	Latte\CompileException::class,
	'Unknown attribute n:inner-class',
);

Assert::exception(
	fn() => $latte->compile('<a n:var=x>'),
	Latte\CompileException::class,
	'Unknown attribute n:var',
);
