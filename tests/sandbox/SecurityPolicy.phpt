<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$policy = new Latte\Sandbox\SecurityPolicy;
$latte->setPolicy($policy);
$latte->setSandboxMode();

Assert::exception(
	fn() => $latte->compile('{$abc}'),
	Latte\CompileException::class,
	'Tag {=} is not allowed.',
);

$policy->allowTags(['=']);

Assert::noError(fn() => $latte->compile('{$abc}'));

Assert::exception(
	fn() => $latte->compile('{var $abc}'),
	Latte\CompileException::class,
	'Tag {var} is not allowed.',
);

$policy->allowTags($policy::ALL);

Assert::noError(fn() => $latte->compile('{var $abc}'));


Assert::exception(
	fn() => $latte->compile('{$abc|upper}'),
	Latte\CompileException::class,
	'Filter |upper is not allowed.',
);

$policy->allowFilters(['UppeR']);

Assert::noError(fn() => $latte->compile('{$abc|upper}'));

Assert::exception(
	fn() => $latte->compile('{$abc|lower}'),
	Latte\CompileException::class,
	'Filter |lower is not allowed.',
);

$policy->allowFilters($policy::ALL);

Assert::noError(fn() => $latte->compile('{$abc|lower}'));


Assert::exception(
	fn() => $latte->compile('{trim(abc)}'),
	Latte\CompileException::class,
	'Function trim() is not allowed.',
);

$policy->allowFunctions(['tRim']);

Assert::noError(function () use ($latte) {
	$latte->compile('{trim(abc)}');
	$latte->renderToString('{="trim"(abc)}');
});

Assert::exception(
	fn() => $latte->compile('{ltrim(abc)}'),
	Latte\CompileException::class,
	'Function ltrim() is not allowed.',
);

$policy->allowFunctions($policy::ALL);

Assert::noError(fn() => $latte->compile('{ltrim(abc)}'));


Assert::exception(
	fn() => $latte->renderToString('{=$obj->format("u")}', ['obj' => new DateTime]),
	Latte\SecurityViolationException::class,
	'Calling DateTime::format() is not allowed.',
);

$policy->allowMethods('dAtetime', ['fOrmat']);

Assert::noError(fn() => $latte->renderToString('{=$obj->format("u")}', ['obj' => new DateTime]));

Assert::exception(
	fn() => $latte->renderToString('{=$obj->getTimestamp()}', ['obj' => new DateTime]),
	Latte\SecurityViolationException::class,
	'Calling DateTime::getTimestamp() is not allowed.',
);

$policy->allowMethods('dAtetime', $policy::ALL);

Assert::noError(fn() => $latte->renderToString('{=$obj->getTimestamp()}', ['obj' => new DateTime]));


Assert::exception(
	fn() => $latte->renderToString('{=$obj->format("u")}', ['obj' => new DateTimeImmutable]),
	Latte\SecurityViolationException::class,
	'Calling DateTimeImmutable::format() is not allowed.',
);

$policy->allowMethods('DateTimeInterface', ['fOrmat']);

Assert::noError(fn() => $latte->renderToString('{=$obj->format("u")}', ['obj' => new DateTimeImmutable]));


Assert::exception(
	fn() => $latte->renderToString('{=$obj->prop}', ['obj' => (object) ['prop' => 123]]),
	Latte\SecurityViolationException::class,
	"Access to 'prop' property on a stdClass object is not allowed.",
);

$policy->allowProperties('sTdClass', ['pRop']);

Assert::noError(fn() => $latte->renderToString('{=$obj->prop}', ['obj' => (object) ['prop' => 123]]));

Assert::exception(
	fn() => $latte->renderToString('{=$obj->prop2}', ['obj' => (object) []]),
	Latte\SecurityViolationException::class,
	"Access to 'prop2' property on a stdClass object is not allowed.",
);

$policy->allowProperties('sTdClass', $policy::ALL);

Assert::noError(fn() => $latte->renderToString('{=$obj->prop2}', ['obj' => (object) ['prop2' => 123]]));
