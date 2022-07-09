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

Assert::exception(
	fn() => $latte->compile('{default $abc}'),
	Latte\SecurityViolationException::class,
	'Tag {default} is not allowed (on line 1 at column 1)',
);

Assert::exception(
	fn() => $latte->compile('<span n:class=""></span>'),
	Latte\SecurityViolationException::class,
	'Attribute n:class is not allowed (on line 1 at column 7)',
);

Assert::exception(
	fn() => $latte->compile('{$abc|upper}'),
	Latte\SecurityViolationException::class,
	'Filter |upper is not allowed (on line 1 at column 6)',
);

Assert::exception(
	fn() => $latte->compile('{$abc|noescape}'),
	Latte\SecurityViolationException::class,
	'Filter |noescape is not allowed (on line 1 at column 6)',
);

Assert::exception(
	fn() => $latte->compile('<a href="{$abc|nocheck}">'),
	Latte\SecurityViolationException::class,
	'Filter |nocheck is not allowed (on line 1 at column 15)',
);

Assert::exception(
	fn() => $latte->compile('<a href="{$abc|datastream}">'),
	Latte\SecurityViolationException::class,
	'Filter |datastream is not allowed (on line 1 at column 15)',
);

Assert::exception(
	fn() => $latte->compile('{trim(123)}'),
	Latte\SecurityViolationException::class,
	'Function trim() is not allowed (on line 1 at column 2)',
);

Assert::exception(
	fn() => $latte->renderToString('{="trim"(123)}'),
	Latte\SecurityViolationException::class,
	'Calling trim() is not allowed.',
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj->error(123)}', ['obj' => new Test]),
	Latte\SecurityViolationException::class,
	'Calling Test::error() is not allowed.',
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj?->error(123)}', ['obj' => new Test]),
	Latte\SecurityViolationException::class,
	'Calling Test::error() is not allowed.',
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj??->error(123)}', ['obj' => new Test]),
	Latte\SecurityViolationException::class,
	'Calling Test::error() is not allowed.',
);

Assert::exception(
	fn() => $latte->renderToString('{=[$obj, "error"](123)}', ['obj' => new Test]),
	Latte\SecurityViolationException::class,
	'Calling Test::error() is not allowed.',
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj->error}', ['obj' => new Test]),
	Latte\SecurityViolationException::class,
	"Access to 'error' property on a Test object is not allowed.",
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj?->error}', ['obj' => new Test]),
	Latte\SecurityViolationException::class,
	"Access to 'error' property on a Test object is not allowed.",
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj??->error}', ['obj' => new Test]),
	Latte\SecurityViolationException::class,
	"Access to 'error' property on a Test object is not allowed.",
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj->$prop}', ['obj' => new Test, 'prop' => 'error']),
	Latte\SecurityViolationException::class,
	"Access to 'error' property on a Test object is not allowed.",
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj::$prop}', ['obj' => new Test]),
	Latte\SecurityViolationException::class,
	"Access to 'prop' property on a Test object is not allowed.",
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj->method()}', ['obj' => 1]),
	Latte\SecurityViolationException::class,
	'Invalid callable.',
);

Assert::exception(
	fn() => $latte->renderToString('{=$obj->$prop}', ['obj' => new Test, 'prop' => 1]),
	Latte\SecurityViolationException::class,
	"Access to '1' property on a Test object is not allowed.",
);

Assert::error(
	fn() => $latte->renderToString('{=$obj->$prop}', ['obj' => 1, 'prop' => 1]),
	E_WARNING,
	'%a% property %a%',
);

Assert::exception(
	fn() => $latte->compile('{$this->filters}'),
	Latte\SecurityViolationException::class,
	'Forbidden variable $this (on line 1 at column 2)',
);

Assert::exception(
	fn() => $latte->compile('{${"this"}}'),
	Latte\SecurityViolationException::class,
	'Forbidden variable variables (on line 1 at column 2)',
);

Assert::exception(
	fn() => $latte->compile('{do echo 123}'),
	Latte\CompileException::class,
	"Keyword 'echo' is forbidden in Latte (on line 1 at column 5)",
);

Assert::exception(
	fn() => $latte->compile('{do return 123}'),
	Latte\CompileException::class,
	"Unexpected 'return' (on line 1 at column 5)",
);

Assert::exception(
	fn() => $latte->compile('{do new stdClass}'),
	Latte\SecurityViolationException::class,
	"Forbidden keyword 'new' (on line 1 at column 5)",
);

Assert::exception(
	fn() => $latte->compile('{var $a = new stdClass}'),
	Latte\SecurityViolationException::class,
	"Forbidden keyword 'new' (on line 1 at column 11)",
);

Assert::exception(
	fn() => $latte->compile('{parameters $a = new stdClass}'),
	Latte\SecurityViolationException::class,
	"Forbidden keyword 'new' (on line 1 at column 18)",
);

Assert::noError(fn() => $latte->compile('{=\'${var}\'}'));
