<?php

/**
 * Test: unknown macro.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(function () use ($latte) {
	$latte->compile('{unknown}');
}, Latte\CompileException::class, 'Unknown tag {unknown}');

Assert::exception(function () use ($latte) {
	$latte->compile('{class}');
}, Latte\CompileException::class, 'Unknown tag {class}');

Assert::exception(function () use ($latte) {
	$latte->compile('{forech}');
}, Latte\CompileException::class, 'Unknown tag {forech}, did you mean {foreach}?');

Assert::exception(function () use ($latte) {
	$latte->compile('<p n:forech>');
}, 'Latte\CompileException', 'Unknown attribute n:forech, did you mean n:foreach?');

Assert::exception(function () use ($latte) {
	$latte->compile('<style>body {color:blue}</style>');
}, Latte\CompileException::class, 'Unknown tag {color:blue} (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)');

Assert::exception(function () use ($latte) {
	$latte->compile('<script>if (true) {return}</script>');
}, Latte\CompileException::class, 'Unknown tag {return} (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)');

Assert::exception(function () use ($latte) {
	$latte->compile('<ul n:abc></ul>');
}, Latte\CompileException::class, 'Unknown attribute n:abc');

Assert::exception(function () use ($latte) {
	$latte->compile('<ul n:abc n:klm></ul>');
}, Latte\CompileException::class, 'Unknown attribute n:abc and n:klm');

Assert::exception(function () use ($latte) {
	$latte->compile('<a n:tag-class=$cond>');
}, Latte\CompileException::class, 'Unknown attribute n:tag-class');

Assert::exception(function () use ($latte) {
	$latte->compile('<a n:inner-class=$cond>');
}, Latte\CompileException::class, 'Unknown attribute n:inner-class');

Assert::exception(function () use ($latte) {
	$latte->compile('<a n:var=x>');
}, Latte\CompileException::class, 'Unknown attribute n:var');
