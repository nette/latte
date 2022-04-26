<?php

/**
 * Test: Compile errors.
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('Block{/block}');
}, Latte\CompileException::class, 'Unexpected {/block}');


Assert::exception(function () use ($latte) {
	$latte->compile('<a {if}n:href>');
}, Latte\CompileException::class, 'n:attribute must not appear inside tags; found n:href inside {if}.');


Assert::exception(function () use ($latte) {
	$latte->compile('<a n:href n:href>');
}, Latte\CompileException::class, 'Found multiple attributes n:href.');


Assert::match(
	'<div c=comment -->',
	$latte->renderToString('<div c=comment {="--"}>')
);


Assert::exception(function () use ($latte) {
	$latte->compile('<a n:class class>');
}, Latte\CompileException::class, 'It is not possible to combine class with n:class.');


Assert::exception(function () use ($latte) {
	$latte->compile('{time() /}');
}, Latte\CompileException::class, 'Unexpected /} in tag {time() /}');


// brackets balaning
Assert::exception(function () use ($latte) {
	$latte->compile('{=)}');
}, Latte\CompileException::class, 'Unexpected )');

Assert::exception(function () use ($latte) {
	$latte->compile('{=[(])}');
}, Latte\CompileException::class, 'Unexpected ]');

Assert::exception(function () use ($latte) {
	$latte->compile('{=[}');
}, Latte\CompileException::class, 'Missing ]');


// forbidden keywords
Assert::exception(function () use ($latte) {
	$latte->compile('{php function test() }');
}, Latte\CompileException::class, "Forbidden keyword 'function' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php function /*comment*/ test() }');
}, Latte\CompileException::class, "Forbidden keyword 'function' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php function &test() }');
}, Latte\CompileException::class, "Forbidden keyword 'function' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php class test }');
}, Latte\CompileException::class, "Forbidden keyword 'class' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php interface test }');
}, Latte\CompileException::class, "Forbidden keyword 'interface' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php return}');
}, Latte\CompileException::class, "Forbidden keyword 'return' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php yield $x}');
}, Latte\CompileException::class, "Forbidden keyword 'yield' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php die() }');
}, Latte\CompileException::class, "Forbidden keyword 'die' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php include "file" }');
}, Latte\CompileException::class, "Forbidden keyword 'include' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{=`whoami`}');
}, Latte\CompileException::class, 'Backtick operator is forbidden in Latte.');

Assert::exception(function () use ($latte) {
	$latte->compile('{=#comment}');
}, Latte\CompileException::class, 'Forbidden # inside tag');

Assert::exception(function () use ($latte) {
	$latte->compile('{=//comment}');
}, Latte\CompileException::class, 'Forbidden // inside tag');

Assert::exception(function () use ($latte) {
	$latte->compile('{$ʟ_tmp}');
}, Latte\CompileException::class, 'Forbidden variable $ʟ_tmp.');


// unclosed macros
Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}');
}, Latte\CompileException::class, 'Missing {/if}');

Assert::exception(function () use ($latte) {
	$latte->compile('<p n:foreach=1><span n:if=1>');
}, Latte\CompileException::class, 'Unexpected end, expecting </span> for n:if');

Assert::exception(function () use ($latte) {
	$latte->compile('<p n:foreach=1><span n:if=1></i>');
}, Latte\CompileException::class, 'Unexpected </i>, expecting </span> for n:if');

Assert::exception(function () use ($latte) {
	$latte->compile('{/if}');
}, Latte\CompileException::class, 'Unexpected {/if}');

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}{/foreach}');
}, Latte\CompileException::class, 'Unexpected {/foreach}, expecting {/if}');

Assert::exception(function () use ($latte) {
	$latte->compile('{if 1}{/if 2}');
}, Latte\CompileException::class, 'Unexpected {/if 2}, expecting {/if}');

Assert::exception(function () use ($latte) {
	$latte->compile('<span n:if=1 n:foreach=2>{foreach}</span>');
}, Latte\CompileException::class, 'Unexpected </span> for n:if and n:foreach, expecting {/foreach}');

Assert::exception(function () use ($latte) {
	$latte->compile('<span n:if=1 n:foreach=2>{/foreach}');
}, Latte\CompileException::class, 'Unexpected {/foreach}, expecting </span> for n:if and n:foreach');

Assert::exception(function () use ($latte) {
	$latte->compile('<span n:if=1 n:foreach=2>{/if}');
}, Latte\CompileException::class, 'Unexpected {/if}, expecting </span> for n:if and n:foreach');

Assert::exception(function () use ($latte) {
	$latte->compile('
	{foreach [] as $item}
		<li><a n:tag-if="$iterator->odd"></li>
	{/foreach}
	');
}, Latte\CompileException::class, 'Unexpected </li>, expecting </a> for n:tag-if (on line 3)');

Assert::error(function () use ($latte) {
	$latte->compile('{=foo|noescape|trim}');
}, E_USER_DEPRECATED, "Filter |noescape should be placed at the very end in '|noescape|trim'");
