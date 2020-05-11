<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setPolicy((new Latte\Sandbox\SecurityPolicy)->allowMacros(['=', 'do']));
$latte->setSandboxMode();

Assert::exception(function () use ($latte) {
	$latte->compile('{var $abc}');
}, Latte\CompileException::class, 'Macro {var} is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('<span n:class=""></span>');
}, Latte\CompileException::class, 'Macro n:class is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('{$abc|upper}');
}, Latte\CompileException::class, 'Filter |upper is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('{$abc|noescape}');
}, Latte\CompileException::class, 'Filter |noescape is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('<a href="{$abc|nocheck}">');
}, Latte\CompileException::class, 'Filter |nocheck is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('<a href="{$abc|datastream}">');
}, Latte\CompileException::class, 'Filter |datastream is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('{trim(123)}');
}, Latte\CompileException::class, 'Function trim() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{="trim"(123)}');
}, Latte\SecurityViolationException::class, 'Calling trim() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->error(123)}', ['obj' => new stdClass]);
}, Latte\SecurityViolationException::class, 'Calling stdClass::error() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=[$obj, "error"](123)}', ['obj' => new stdClass]);
}, Latte\SecurityViolationException::class, 'Calling stdClass::error() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->error}', ['obj' => new stdClass]);
}, Latte\SecurityViolationException::class, "Access to 'error' property on a stdClass object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->$prop}', ['obj' => new stdClass, 'prop' => 'error']);
}, Latte\SecurityViolationException::class, "Access to 'error' property on a stdClass object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj::$prop}', ['obj' => new stdClass]);
}, Latte\SecurityViolationException::class, "Access to 'prop' property on a stdClass object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->compile('{=`whoami`}');
}, Latte\CompileException::class, 'Forbidden backtick operator.');

Assert::exception(function () use ($latte) {
	$latte->compile('{$this->filters}');
}, Latte\CompileException::class, 'Forbidden variable $this.');

Assert::exception(function () use ($latte) {
	$latte->compile('{do echo 123}');
}, Latte\CompileException::class, "Forbidden keyword 'echo' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{do return 123}');
}, Latte\CompileException::class, "Forbidden keyword 'return' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{do yield 123}');
}, Latte\CompileException::class, "Forbidden keyword 'yield' inside macro.");

Assert::exception(function () use ($latte) {
	$latte->compile('{do new stdClass}');
}, Latte\CompileException::class, "Forbidden keyword 'new' inside macro.");
