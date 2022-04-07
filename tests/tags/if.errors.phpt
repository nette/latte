<?php

/**
 * Test: Latte\Macros\CoreMacros: {if ...}
 */

declare(strict_types=1);

use Latte\Macros\CoreMacros;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler\TemplateGenerator;
CoreMacros::install($compiler);

Assert::same('<?php if (isset($var)) { ?>', $compiler->expandMacro('ifset', '$var')->openingCode);
Assert::same('<?php if (isset($item->var["test"])) { ?>', $compiler->expandMacro('ifset', '$item->var["test"]')->openingCode);

Assert::exception(
	fn() => $compiler->expandMacro('if', '$var', '|filter'),
	Latte\CompileException::class,
	'Filters are not allowed in {if}',
);

Assert::exception(
	fn() => $compiler->expandMacro('ifset', '$var', '|filter'),
	Latte\CompileException::class,
	'Filters are not allowed in {ifset}',
);


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{if 1}{else if a}{/if}'),
	Latte\CompileException::class,
	'Arguments are not allowed in {else}, did you mean {elseif}?',
);

Assert::exception(
	fn() => $latte->compile('{if 1}{else a}{/if}'),
	Latte\CompileException::class,
	'Arguments are not allowed in {else}',
);

Assert::exception(
	fn() => $latte->compile('{else}'),
	Latte\CompileException::class,
	'Tag {else} is unexpected here.',
);

Assert::exception(
	fn() => $latte->compile('{if 1}{else}{else}{/if}'),
	Latte\CompileException::class,
	'Tag {if} may only contain one {else} clause.',
);

Assert::exception(
	fn() => $latte->compile('{elseif a}'),
	Latte\CompileException::class,
	'Tag {elseif} is unexpected here.',
);

Assert::exception(
	fn() => $latte->compile('{if 1}{else}{elseif a}{/if}'),
	Latte\CompileException::class,
	'Tag {elseif} is unexpected here.',
);

Assert::exception(
	fn() => $latte->compile('{if}{elseif a}{/if 1}'),
	Latte\CompileException::class,
	'Tag {elseif} is unexpected here.',
);
