<?php declare(strict_types=1);

/**
 * Test: {foreach} with scoped variables
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function testScoped(string $title, string $template, string $expected, array $params = [])
{
	$latte = createLatte();
	$latte->setFeature(Latte\Feature::ScopedLoopVariables);
	Assert::same($expected, $latte->renderToString($template, $params), $title);
}


// Default behavior: variables leak outside the loop (no scoping)
$latte = createLatte();
$code = $latte->compile('{foreach [1, 2] as $v}{/foreach}');
Assert::contains('foreach (', $code);
Assert::notContains('try {', $code);


// With ScopedLoopVariables: generates scope save/restore code
$latte = createLatte();
$latte->setFeature(Latte\Feature::ScopedLoopVariables);
$code = $latte->compile('{foreach [1, 2] as $v}{/foreach}');
Assert::contains('try {', $code);
Assert::contains('finally {', $code);


testScoped(
	'variable restored after loop',
	'{var $v = "X"}before={$v} {foreach [1, 2] as $v}loop={$v} {/foreach}after={$v}',
	'before=X loop=1 loop=2 after=X',
);


testScoped(
	'null variable restored after loop',
	'{var $v = null}before={$v ?? "null"} {foreach [1, 2] as $v}loop={$v} {/foreach}after={$v ?? "null"}{array_key_exists("v", get_defined_vars()) ? " exists" : " !exists"}',
	'before=null loop=1 loop=2 after=null exists',
);


testScoped(
	'variable unset after loop (did not exist before)',
	'{foreach [1, 2] as $v}loop={$v} {/foreach}after={$v ?? "unset"}',
	'loop=1 loop=2 after=unset',
);


testScoped(
	'key and value both scoped',
	'{foreach ["a", "b"] as $k => $v}loop={$k}:{$v} {/foreach}after={$k ?? "unset"}:{$v ?? "unset"}',
	'loop=0:a loop=1:b after=unset:unset',
);


testScoped(
	'destructuring: all variables scoped',
	'{foreach [[1, 2], [3, 4]] as [$a, $b]}loop={$a}-{$b} {/foreach}after={$a ?? "unset"}:{$b ?? "unset"}',
	'loop=1-2 loop=3-4 after=unset:unset',
);


// Property access ($obj->val): not a simple variable, no scoping
$latte = createLatte();
$latte->setFeature(Latte\Feature::ScopedLoopVariables);
$code = $latte->compile('{var $obj = new stdClass}{foreach [1, 2] as $obj->val}{$obj->val}{/foreach}');
Assert::notContains('try {', $code);


// Reference in foreach ({foreach as &$v}): scoping disabled, would break reference semantics
$latte = createLatte();
$latte->setFeature(Latte\Feature::ScopedLoopVariables);
$code = $latte->compile('{foreach $arr as &$v}{$v}{/foreach}');
Assert::notContains('try {', $code);


// Dynamic variable name ({foreach as ${"'"}}): scoping disabled, only simple names supported
$latte = createLatte();
$latte->setFeature(Latte\Feature::ScopedLoopVariables);
$code = $latte->compile('{foreach [1] as ${"\'"}}{/foreach}');
Assert::notContains('try {', $code);


testScoped(
	'{else} branch (non-empty)',
	'{foreach [1, 2] as $v}loop={$v} {else}empty {/foreach}after={$v ?? "unset"}',
	'loop=1 loop=2 after=unset',
);

testScoped(
	'{else} branch (empty)',
	'{foreach [] as $v}loop={$v} {else}empty {/foreach}after={$v ?? "unset"}',
	'empty after=unset',
);


// overwrittenVariablesPass: skipped when ScopedLoopVariables enabled (no warning)
$latte = createLatte();
$latte->setFeature(Latte\Feature::ScopedLoopVariables);
Assert::noError(fn() => $latte->renderToString('{foreach [1] as $v}{/foreach}', ['v' => 'param']));


testScoped(
	'empty foreach body',
	'{var $v = "X"}before={$v} {foreach [1, 2] as $v}{/foreach}after={$v}',
	'before=X after=X',
);


testScoped(
	'reference preservation',
	'{var $v = "X", $ref = &$v}{foreach [1] as $v}{/foreach}v={$v} ref={$ref}',
	'v=X ref=X',
);


testScoped(
	'nested loops: independent scopes',
	'{foreach ["A", "B"] as $v}outer={$v} {foreach ["x", "y"] as $v}inner={$v} {/foreach}restored={$v} {/foreach}after={$v ?? "unset"}',
	'outer=A inner=x inner=y restored=A outer=B inner=x inner=y restored=B after=unset',
);


// Cleanup: helper array entries are unset after restore
$latte = createLatte();
$latte->setFeature(Latte\Feature::ScopedLoopVariables);
Assert::matchFile(
	__DIR__ . '/expected/foreach.scopedVariables.php',
	$latte->compile('{foreach [1] as $v}{/foreach}'),
);
