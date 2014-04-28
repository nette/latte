<?php

/**
 * Test: Latte\Engine: unknown macro.
 *
 * @author     David Grudl
 */

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function() use ($latte) {
	$latte->compile('{unknown}');
}, 'Latte\CompileException', 'Unknown macro {unknown}');

Assert::exception(function() use ($latte) {
	$latte->compile('{class}');
}, 'Latte\CompileException', 'Unhandled macro {class}');

Assert::exception(function() use ($latte) {
	$latte->compile('<style>body {color:blue}</style>');
}, 'Latte\CompileException', 'Unknown macro {color:blue} (in JavaScript or CSS, try to put a space after bracket.)');

Assert::exception(function() use ($latte) {
	$latte->compile('<script>if (true) {return}</script>');
}, 'Latte\CompileException', 'Unknown macro {return} (in JavaScript or CSS, try to put a space after bracket.)');

Assert::exception(function() use ($latte) {
	$latte->compile('<ul n:abc></ul>');
}, 'Latte\CompileException', 'Unknown macro-attribute n:abc');

Assert::exception(function() use ($latte) {
	$latte->compile('<ul n:abc n:klm></ul>');
}, 'Latte\CompileException', 'Unknown macro-attribute n:abc and n:klm');

Assert::exception(function() use ($latte) {
	$latte->compile('<a n:tag-class=$cond>');
}, 'Latte\CompileException', 'Unknown macro-attribute n:tag-class');

Assert::exception(function() use ($latte) {
	$latte->compile('<a n:inner-class=$cond>');
}, 'Latte\CompileException', 'Unknown macro-attribute n:inner-class');
