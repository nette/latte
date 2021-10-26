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
	$latte->compile('{forech}');
}, Latte\CompileException::class, 'Unknown tag {forech}, did you mean {foreach}?');


Assert::exception(function () use ($latte) {
	$latte->compile('<p n:forech>');
}, 'Latte\CompileException', 'Unknown attribute n:forech, did you mean n:foreach?');


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

Assert::noError(function () use ($latte) {
	$latte->compile('{php Foo::class }');
});

Assert::noError(function () use ($latte) {
	$latte->compile('{php $obj->interface }');
	$latte->compile('{php $obj?->interface }');
	$latte->compile('{php $obj??->interface }');
});

Assert::exception(function () use ($latte) {
	$latte->compile('{php interface test }');
}, Latte\CompileException::class, "Forbidden keyword 'interface' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php return}');
}, Latte\CompileException::class, "Forbidden keyword 'return' inside tag.");

Assert::noError(function () use ($latte) {
	$latte->compile('{php function () { return; }}');
});

Assert::exception(function () use ($latte) {
	$latte->compile('{php yield $x}');
}, Latte\CompileException::class, "Forbidden keyword 'yield' inside tag.");

Assert::noError(function () use ($latte) {
	$latte->compile('{php function () { yield $x; }}');
});

Assert::exception(function () use ($latte) {
	$latte->compile('{php die() }');
}, Latte\CompileException::class, "Forbidden keyword 'die' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php include "file" }');
}, Latte\CompileException::class, "Forbidden keyword 'include' inside tag.");

Assert::error(function () use ($latte) {
	$latte->compile('{=`whoami`}');
}, Latte\CompileException::class, 'Backtick operator is forbidden in Latte.');

Assert::error(function () use ($latte) {
	$latte->compile('{=#comment}');
}, Latte\CompileException::class, 'Forbidden # inside tag');

Assert::error(function () use ($latte) {
	$latte->compile('{=//comment}');
}, Latte\CompileException::class, 'Forbidden // inside tag');

Assert::exception(function () use ($latte) {
	$latte->compile('{$ʟ_tmp}');
}, Latte\CompileException::class, 'Forbidden variable $ʟ_tmp.');
