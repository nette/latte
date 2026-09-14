<?php declare(strict_types=1);

/**
 * Test: the sandbox keeps the semantics of -> and ?->
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Obj
{
	public $prop;


	public function join(...$args)
	{
		return implode('', $args);
	}


	public function nothing()
	{
		return null;
	}
}


$policy = new Latte\Sandbox\SecurityPolicy;
$policy->allowTags($policy::All);
$policy->allowMethods(Obj::class, $policy::All);
$policy->allowProperties(Obj::class, $policy::All);
$latte = createLatte();
$latte->setPolicy($policy);
$latte->setSandboxMode();


test('nullsafe call on null skips the arguments', function () use ($latte) {
	$calls = 0;
	$fn = function () use (&$calls) {
		$calls++;
	};
	Assert::same('', $latte->renderToString('{=$obj?->join($fn())}', ['obj' => null, 'fn' => $fn]));
	Assert::same(0, $calls);
});


test('call on null throws', function () use ($latte) {
	Assert::exception(
		fn() => $latte->renderToString('{=$obj->join()}', ['obj' => null]),
		Error::class,
		'Call to a member function join() on null',
	);
});


test('nullsafe short-circuits the rest of the chain', function () use ($latte) {
	Assert::same('', $latte->renderToString('{=$obj?->nothing()->join()}', ['obj' => null]));
	Assert::same('', $latte->renderToString('{=$obj?->prop->prop}', ['obj' => null]));
	Assert::same('', $latte->renderToString('{=$obj?->prop["x"]->join()}', ['obj' => null]));
});


test('arguments are passed', function () use ($latte) {
	Assert::same('ab', $latte->renderToString('{=$obj->join("a", "b")}', ['obj' => new Obj]));
	Assert::same('ab', $latte->renderToString('{=$obj->join(...["a", "b"])}', ['obj' => new Obj]));
});
