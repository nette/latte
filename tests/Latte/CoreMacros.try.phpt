<?php

/**
 * Test: {try} ... {catch} ... {/try}
 */

declare(strict_types=1);

use Latte\Macros\CoreMacros;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

Assert::exception(function () use ($compiler) {
	$compiler->expandMacro('try', '', '|filter');
}, Latte\CompileException::class, 'Neither arguments nor modifiers are allowed in {try}');

Assert::exception(function () use ($compiler) {
	$compiler->expandMacro('try', '$var', '');
}, Latte\CompileException::class, 'Neither arguments nor modifiers are allowed in {try}');

Assert::exception(function () use ($compiler) {
	$compiler->expandMacro('catch', 'if args');
}, Latte\CompileException::class, 'Neither arguments nor modifiers are allowed in {catch}');

Assert::exception(function () use ($compiler) {
	$compiler->expandMacro('catch', '');
}, Latte\CompileException::class, 'Macro {catch} must be inside {try} ... {/try}.');


function error()
{
	throw new \Exception;
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(<<<'XX'
top begin
	in1 in2

top end
XX
,
	$latte->renderToString(<<<'XX'
top begin
{try}
	in1 in2
{/try}
top end
XX
));


Assert::match(<<<'XX'
top begin

top end
XX
,
	$latte->renderToString(<<<'XX'
top begin
{try}
	in1 {=error()} in2
{/try}
top end
XX
));


Assert::match(<<<'XX'
top begin
	in1 in2
	top end
XX
,
	$latte->renderToString(<<<'XX'
top begin
{try}
	in1 in2
	{catch}
	error
{/try}
top end
XX
));


Assert::match(<<<'XX'
top begin

	error
top end
XX
,
	$latte->renderToString(<<<'XX'
top begin
{try}
	in1 {=error()} in2
	{catch}
	error
{/try}
top end
XX
));
