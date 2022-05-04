<?php

/**
 * Test: Latte\Macros\CoreMacros: {foreach ...}
 */

declare(strict_types=1);

use Latte\Macros\CoreMacros;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$compiler = new Latte\Compiler;
CoreMacros::install($compiler);

$prefix = '<?php $iterations = 0; '
	. 'foreach ($iterator = $ʟ_it = new LR\CachingIterator(';


function expandMacro($compiler, $args, $modifiers = '')
{
	$node = $compiler->expandMacro('foreach', $args, $modifiers);
	$node->content = ' $iterator ';
	$node->closing = true;
	$node->macro->nodeClosed($node);
	return $node;
}


Assert::same($prefix . '$array, $ʟ_it ?? null) as $value) { ?>', expandMacro($compiler, '$array as $value')->openingCode);
Assert::same(
	'<?php $iterations = 0; '
	. 'foreach ($iterator = $ʟ_it = new LR\CachingIterator($array, $ʟ_it ?? null) as $key => $value) { ?>',
	expandMacro($compiler, '$array as $key => $value')->openingCode,
);

Assert::same(
	'<?php $iterations = 0; '
	. 'foreach ($iterator = $ʟ_it = new LR\CachingIterator($array, $ʟ_it ?? null) as $key => $value) { ?>',
	expandMacro($compiler, '$array as $key => $value', '|nocheck')->openingCode,
);

Assert::same($prefix . '$obj->data("A as B"), $ʟ_it ?? null) as $value) { ?>', expandMacro($compiler, '$obj->data("A as B") as $value')->openingCode);
Assert::same($prefix . '$obj->data(\'A as B\'), $ʟ_it ?? null) as $value) { ?>', expandMacro($compiler, '$obj->data(\'A as B\') as $value')->openingCode);
Assert::same($prefix . '$obj->data("X as Y, Z as W"), $ʟ_it ?? null) as $value) { ?>', expandMacro($compiler, '$obj->data("X as Y, Z as W") as $value')->openingCode);

Assert::same(
	'<?php $iterations = 0; '
	. 'foreach ($array as $value) { ?>',
	expandMacro($compiler, '$array as $value', '|noiterator')->openingCode,
);

Assert::exception(
	fn() => expandMacro($compiler, '$array as $value', '|filter'),
	Latte\CompileException::class,
	'Only modifiers |noiterator and |nocheck are allowed here.',
);


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{foreach}{/foreach}'),
	Latte\CompileException::class,
	'Missing arguments in {foreach}',
);
