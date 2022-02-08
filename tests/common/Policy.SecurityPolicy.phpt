<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$policy = new Latte\Sandbox\SecurityPolicy;
$latte->setPolicy($policy);
$latte->setSandboxMode();

Assert::exception(function () use ($latte) {
	$latte->compile('{$abc}');
}, Latte\CompileException::class, 'Tag {=} is not allowed.');

$policy->allowMacros(['=']);

Assert::noError(function () use ($latte) {
	$latte->compile('{$abc}');
});

Assert::exception(function () use ($latte) {
	$latte->compile('{var $abc}');
}, Latte\CompileException::class, 'Tag {var} is not allowed.');

$policy->allowMacros($policy::ALL);

Assert::noError(function () use ($latte) {
	$latte->compile('{var $abc}');
});


Assert::exception(function () use ($latte) {
	$latte->compile('{$abc|upper}');
}, Latte\CompileException::class, 'Filter |upper is not allowed.');

$policy->allowFilters(['UppeR']);

Assert::noError(function () use ($latte) {
	$latte->compile('{$abc|upper}');
});

Assert::exception(function () use ($latte) {
	$latte->compile('{$abc|lower}');
}, Latte\CompileException::class, 'Filter |lower is not allowed.');

$policy->allowFilters($policy::ALL);

Assert::noError(function () use ($latte) {
	$latte->compile('{$abc|lower}');
});


Assert::exception(function () use ($latte) {
	$latte->compile('{trim(123)}');
}, Latte\CompileException::class, 'Function trim() is not allowed.');

$policy->allowFunctions(['tRim']);

Assert::noError(function () use ($latte) {
	$latte->compile('{trim(123)}');
	$latte->renderToString('{="trim"(123)}');
});

Assert::exception(function () use ($latte) {
	$latte->compile('{ltrim(123)}');
}, Latte\CompileException::class, 'Function ltrim() is not allowed.');

$policy->allowFunctions($policy::ALL);

Assert::noError(function () use ($latte) {
	$latte->compile('{ltrim(123)}');
});


Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->format("u")}', ['obj' => new DateTime]);
}, Latte\SecurityViolationException::class, 'Calling DateTime::format() is not allowed.');

$policy->allowMethods('dAtetime', ['fOrmat']);

Assert::noError(function () use ($latte) {
	$latte->renderToString('{=$obj->format("u")}', ['obj' => new DateTime]);
});

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->getTimestamp()}', ['obj' => new DateTime]);
}, Latte\SecurityViolationException::class, 'Calling DateTime::getTimestamp() is not allowed.');

$policy->allowMethods('dAtetime', $policy::ALL);

Assert::noError(function () use ($latte) {
	$latte->renderToString('{=$obj->getTimestamp()}', ['obj' => new DateTime]);
});


Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->format("u")}', ['obj' => new DateTimeImmutable]);
}, Latte\SecurityViolationException::class, 'Calling DateTimeImmutable::format() is not allowed.');

$policy->allowMethods('DateTimeInterface', ['fOrmat']);

Assert::noError(function () use ($latte) {
	$latte->renderToString('{=$obj->format("u")}', ['obj' => new DateTimeImmutable]);
});


Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->prop}', ['obj' => (object) ['prop' => 123]]);
}, Latte\SecurityViolationException::class, "Access to 'prop' property on a stdClass object is not allowed.");

$policy->allowProperties('sTdClass', ['pRop']);

Assert::noError(function () use ($latte) {
	$latte->renderToString('{=$obj->prop}', ['obj' => (object) ['prop' => 123]]);
});

Assert::exception(function () use ($latte) {
	$latte->renderToString('{=$obj->prop2}', ['obj' => (object) []]);
}, Latte\SecurityViolationException::class, "Access to 'prop2' property on a stdClass object is not allowed.");

$policy->allowProperties('sTdClass', $policy::ALL);

Assert::noError(function () use ($latte) {
	$latte->renderToString('{=$obj->prop2}', ['obj' => (object) ['prop2' => 123]]);
});
