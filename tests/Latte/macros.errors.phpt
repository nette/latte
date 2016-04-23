<?php

/**
 * Test: Latte\Engine: errors.
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
	'<div c=comment invalid>',
	$latte->renderToString('<div c=comment {="--"}>')
);


Assert::exception(function () use ($latte) {
	$latte->compile('<a n:class class>');
}, 'Latte\CompileException', 'It is not possible to combine class with n:class.');



Assert::exception(function () use ($latte) {
	$latte->compile('{forech}');
}, 'Latte\CompileException', 'Unknown macro {forech}, did you mean {foreach}?');

Assert::match(
    '<div c=comment -->',
    $latte->renderToString('<div c=comment {="--"|noescape}>')
);