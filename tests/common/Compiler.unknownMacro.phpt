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
	'Unexpected tag {unknown} (on line 1 at column 1)',
);

Assert::exception(
	fn() => $latte->compile('{class}'),
	Latte\CompileException::class,
	'Unexpected tag {class}, did you mean {last}? (on line 1 at column 1)',
);

Assert::exception(
	fn() => $latte->compile('{forech}'),
	Latte\CompileException::class,
	'Unexpected tag {forech}, did you mean {foreach}? (on line 1 at column 1)',
);

Assert::exception(
	fn() => $latte->compile('<p n:forech>'),
	'Latte\CompileException',
	'Unexpected attribute n:forech, did you mean n:foreach? (on line 1 at column 4)',
);

Assert::exception(
	fn() => $latte->compile('<style>body {color:blue}</style>'),
	Latte\CompileException::class,
	'Unexpected tag {color:blue} (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off) (on line 1 at column 13)',
);

Assert::exception(
	fn() => $latte->compile('<script>if (true) {return}</script>'),
	Latte\CompileException::class,
	'Unexpected tag {return} (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off) (on line 1 at column 19)',
);

Assert::exception(
	fn() => $latte->compile('<ul n:abc></ul>'),
	Latte\CompileException::class,
	'Unexpected attribute n:abc (on line 1 at column 5)',
);

Assert::exception(
	fn() => $latte->compile('<ul n:abc n:klm></ul>'),
	Latte\CompileException::class,
	'Unexpected attribute n:abc and n:klm (on line 1 at column 5)',
);

Assert::exception(
	fn() => $latte->compile('<a n:tag-class=$cond>'),
	Latte\CompileException::class,
	'Unexpected attribute n:tag-class, did you mean n:tag-last? (on line 1 at column 4)',
);

Assert::exception(
	fn() => $latte->compile('<a n:inner-class=$cond>'),
	Latte\CompileException::class,
	'Unexpected attribute n:inner-class, did you mean n:inner-last? (on line 1 at column 4)',
);

Assert::exception(
	fn() => $latte->compile('<a n:var=x>'),
	Latte\CompileException::class,
	'Unexpected attribute n:var (on line 1 at column 4)',
);
