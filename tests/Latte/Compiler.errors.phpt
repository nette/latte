<?php

/**
 * Test: Compile errors.
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('<a {if}n:href>');
}, 'Latte\CompileException', 'n:attributes must not appear inside macro; found n:href inside {if}.');


Assert::exception(function () use ($latte) {
	$latte->compile('<a n:href n:href>');
}, 'Latte\CompileException', 'Found multiple attributes n:href.');


Assert::match(
	'<div c=comment -->',
	$latte->renderToString('<div c=comment {="--"}>')
);


Assert::exception(function () use ($latte) {
	$latte->compile('<a n:class class>');
}, 'Latte\CompileException', 'It is not possible to combine class with n:class.');


Assert::exception(function () use ($latte) {
	$latte->compile('{forech}');
}, 'Latte\CompileException', 'Unknown macro {forech}, did you mean {foreach}?');


Assert::exception(function () use ($latte) {
	$latte->compile('{time() /}');
}, 'Latte\CompileException', 'Unexpected /} in tag {time() /}');


// brackets balaning
Assert::exception(function () use ($latte) {
	$latte->compile('{=)}');
}, 'Latte\CompileException', 'Unexpected )');

Assert::exception(function () use ($latte) {
	$latte->compile('{=[(])}');
}, 'Latte\CompileException', 'Unexpected ]');

Assert::exception(function () use ($latte) {
	$latte->compile('{=[}');
}, 'Latte\CompileException', 'Missing ]');


// forbidden keywords
Assert::exception(function () use ($latte) {
	$latte->compile('{php function test() }');
}, 'Latte\CompileException', "Forbidden keyword 'function' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php function /*comment*/ test() }');
}, 'Latte\CompileException', "Forbidden keyword 'function' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php function & test() }');
}, 'Latte\CompileException', "Forbidden keyword 'function' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php class test }');
}, 'Latte\CompileException', "Forbidden keyword 'class' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php interface test }');
}, 'Latte\CompileException', "Forbidden keyword 'interface' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php return}');
}, 'Latte\CompileException', "Forbidden keyword 'return' inside macro.");

Assert::noError(function () use ($latte) {
	$latte->compile('{php function () { return; }}');
});

Assert::exception(function () use ($latte) {
	$latte->compile('{php yield}');
}, 'Latte\CompileException', "Forbidden keyword 'yield' inside macro.");

Assert::noError(function () use ($latte) {
	$latte->compile('{php function () { yield; }}');
});
