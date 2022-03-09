<?php

/**
 * Test: {try} ... {else} {rollback} ... {/try}
 */

declare(strict_types=1);

use Latte\Macros\CoreMacros;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler\Compiler;
CoreMacros::install($compiler);

Assert::exception(
	fn() => $compiler->expandMacro('try', '', '|filter'),
	Latte\CompileException::class,
	'Filters are not allowed in {try}',
);

Assert::exception(
	fn() => $compiler->expandMacro('try', '$var', ''),
	Latte\CompileException::class,
	'Arguments are not allowed in {try}',
);


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{rollback}'),
	Latte\CompileException::class,
	'Tag {rollback} must be inside {try} ... {/try}.',
);
