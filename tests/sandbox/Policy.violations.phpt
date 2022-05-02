<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test
{
	public function __call($nm, $arg)
	{
	}
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setPolicy((new Latte\Sandbox\SecurityPolicy)->allowTags(['=', 'do', 'var', 'parameters']));
$latte->setSandboxMode();

Assert::exception(function () use ($latte) {
	$latte->compile('{default $abc}');
}, Latte\CompileException::class, 'Tag {default} is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->compile('<span n:class=""></span>');
}, Latte\CompileException::class, 'Tag n:class is not allowed.');

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
	$latte->renderToString('{=$obj->error(123)}', ['obj' => new Test]);
}, Latte\SecurityViolationException::class, 'Calling Test::error() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj?->error(123)}', ['obj' => new Test]);
}, Latte\SecurityViolationException::class, 'Calling Test::error() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj??->error(123)}', ['obj' => new Test]);
}, Latte\SecurityViolationException::class, 'Calling Test::error() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=[$obj, "error"](123)}', ['obj' => new Test]);
}, Latte\SecurityViolationException::class, 'Calling Test::error() is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->error}', ['obj' => new Test]);
}, Latte\SecurityViolationException::class, "Access to 'error' property on a Test object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj?->error}', ['obj' => new Test]);
}, Latte\SecurityViolationException::class, "Access to 'error' property on a Test object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj??->error}', ['obj' => new Test]);
}, Latte\SecurityViolationException::class, "Access to 'error' property on a Test object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->$prop}', ['obj' => new Test, 'prop' => 'error']);
}, Latte\SecurityViolationException::class, "Access to 'error' property on a Test object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj::$prop}', ['obj' => new Test]);
}, Latte\SecurityViolationException::class, "Access to 'prop' property on a Test object is not allowed.");

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->method()}', ['obj' => 1]);
}, Latte\SecurityViolationException::class, 'Invalid callable.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->$prop}', ['obj' => new Test, 'prop' => 1]);
}, Latte\SecurityViolationException::class, "Access to '1' property on a Test object is not allowed.");

Assert::error(function () use ($latte) {
	$latte->renderToString('{=$obj->$prop}', ['obj' => 1, 'prop' => 1]);
}, E_WARNING, '%a% property %a%');

Assert::exception(function () use ($latte) {
	$latte->compile('{$this->filters}');
}, Latte\CompileException::class, 'Forbidden variable $this.');

Assert::exception(function () use ($latte) {
	$latte->compile('{${"this"}}');
}, Latte\CompileException::class, 'Forbidden variable variables.');

Assert::exception(function () use ($latte) {
	$latte->compile('{$$x}}');
}, Latte\CompileException::class, 'Forbidden variable variables.');

Assert::exception(function () use ($latte) {
	$latte->compile('{do echo 123}');
}, Latte\CompileException::class, "Forbidden keyword 'echo' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{do return 123}');
}, Latte\CompileException::class, "Forbidden keyword 'return' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{do yield 123}');
}, Latte\CompileException::class, "Forbidden keyword 'yield' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{do new stdClass}');
}, Latte\CompileException::class, "Forbidden keyword 'new' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{var $a = new stdClass}');
}, Latte\CompileException::class, "Forbidden keyword 'new' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{parameters $a = new stdClass}');
}, Latte\CompileException::class, "Forbidden keyword 'new' inside tag.");

Assert::exception(function () use ($latte) {
	$latte->compile('{="{$var}"}');
}, Latte\CompileException::class, 'Forbidden complex expressions in strings.');

Assert::exception(function () use ($latte) {
	$latte->compile('{="${var}"}');
}, Latte\CompileException::class, 'Forbidden complex expressions in strings.');

Assert::noError(function () use ($latte) {
	$latte->compile('{=\'${var}\'}');
});
