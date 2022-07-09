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
	Latte\SecurityViolationException::class,
	'Tag {=} is not allowed (on line 1 at column 1)',
);

$policy->allowTags(['=']);

Assert::noError(fn() => $latte->compile('{$abc}'));

Assert::exception(
	fn() => $latte->compile('{var $abc}'),
	Latte\SecurityViolationException::class,
	'Tag {var} is not allowed (on line 1 at column 1)',
);

$policy->allowTags($policy::All);

Assert::noError(fn() => $latte->compile('{var $abc}'));


Assert::exception(
	fn() => $latte->compile('{$abc|upper}'),
	Latte\SecurityViolationException::class,
	'Filter |upper is not allowed (on line 1 at column 6)',
);

$policy->allowFilters(['UppeR']);

Assert::noError(fn() => $latte->compile('{$abc|upper}'));

Assert::exception(
	fn() => $latte->compile('{$abc|lower}'),
	Latte\SecurityViolationException::class,
	'Filter |lower is not allowed (on line 1 at column 6)',
);

$policy->allowFilters($policy::All);

Assert::noError(fn() => $latte->compile('{$abc|lower}'));


Assert::exception(
	fn() => $latte->compile('{trim(abc)}'),
	Latte\SecurityViolationException::class,
	'Function trim() is not allowed (on line 1 at column 2)',
);

$policy->allowFunctions(['tRim']);

Assert::noError(function () use ($latte) {
	$latte->compile('{trim(abc)}');
	$latte->renderToString('{="trim"(abc)}');
});

Assert::exception(
	fn() => $latte->compile('{ltrim(abc)}'),
	Latte\SecurityViolationException::class,
	'Function ltrim() is not allowed (on line 1 at column 2)',
);

$policy->allowFunctions($policy::All);

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

$policy->allowMethods('dAtetime', $policy::All);

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

$policy->allowProperties('sTdClass', $policy::All);

Assert::noError(fn() => $latte->renderToString('{=$obj->prop2}', ['obj' => (object) ['prop2' => 123]]));
