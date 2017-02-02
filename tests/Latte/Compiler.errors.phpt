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
}, Latte\CompileException::class, 'n:attributes must not appear inside macro; found n:href inside {if}.');


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
}, Latte\CompileException::class, 'Unknown macro {forech}, did you mean {foreach}?');


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
}, Latte\CompileException::class, "Forbidden keyword 'function' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php function /*comment*/ test() }');
}, Latte\CompileException::class, "Forbidden keyword 'function' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php function &test() }');
}, Latte\CompileException::class, "Forbidden keyword 'function' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php class test }');
}, Latte\CompileException::class, "Forbidden keyword 'class' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php interface test }');
}, Latte\CompileException::class, "Forbidden keyword 'interface' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{php return}');
}, Latte\CompileException::class, "Forbidden keyword 'return' inside macro.");

Assert::noError(function () use ($latte) {
	$latte->compile('{php function () { return; }}');
});

Assert::exception(function () use ($latte) {
	$latte->compile('{php yield}');
}, Latte\CompileException::class, "Forbidden keyword 'yield' inside macro.");

Assert::noError(function () use ($latte) {
	$latte->compile('{php function () { yield; }}');
});
