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
}, Latte\CompileException::class, 'Unexpected tag {unknown}');

Assert::exception(function () use ($latte) {
	$latte->compile('{class}');
}, Latte\CompileException::class, 'Unexpected tag {class}, did you mean {last}?');

Assert::exception(function () use ($latte) {
	$latte->compile('<style>body {color:blue}</style>');
}, Latte\CompileException::class, 'Unexpected tag {color:blue} (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)');

Assert::exception(function () use ($latte) {
	$latte->compile('<script>if (true) {return}</script>');
}, Latte\CompileException::class, 'Unexpected tag {return} (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)');

Assert::exception(function () use ($latte) {
	$latte->compile('<ul n:abc></ul>');
}, Latte\CompileException::class, 'Unexpected attribute n:abc');

Assert::exception(function () use ($latte) {
	$latte->compile('<ul n:abc n:klm></ul>');
}, Latte\CompileException::class, 'Unexpected attribute n:abc and n:klm');

Assert::exception(function () use ($latte) {
	$latte->compile('<a n:tag-class=$cond>');
}, Latte\CompileException::class, 'Unexpected attribute n:tag-class, did you mean n:tag-last?');

Assert::exception(function () use ($latte) {
	$latte->compile('<a n:inner-class=$cond>');
}, Latte\CompileException::class, 'Unexpected attribute n:inner-class, did you mean n:inner-last?');

Assert::exception(function () use ($latte) {
	$latte->compile('<a n:var=x>');
}, Latte\CompileException::class, 'Unexpected attribute n:var');
