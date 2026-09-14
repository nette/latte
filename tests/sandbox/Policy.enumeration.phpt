<?php declare(strict_types=1);

/**
 * Test: reading all properties of an object at once is subject to the policy.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Record
{
	public $visible = 'visible';
	public $secret = 'secret';
	private $private = 'private';
}


$policy = new Latte\Sandbox\SecurityPolicy;
$policy->allowTags($policy::All);
$policy->allowProperties(Record::class, ['visible']);
$latte = createLatte();
$latte->setPolicy($policy);
$latte->setSandboxMode();


test('an object cannot be cast to array', function () use ($latte) {
	Assert::exception(
		fn() => $latte->renderToString('{foreach (array) $obj as $v}{$v}{/foreach}', ['obj' => new Record]),
		Latte\SecurityViolationException::class,
		'Casting a Record object to array is not allowed.',
	);
	Assert::same('ab', $latte->renderToString('{foreach (array) $val as $v}{$v}{/foreach}', ['val' => ['a', 'b']]));
	Assert::same('a', $latte->renderToString('{foreach (array) $val as $v}{$v}{/foreach}', ['val' => 'a']));
});


test('iterating a plain object reads its properties', function () use ($latte, $policy) {
	Assert::exception(
		fn() => $latte->renderToString('{foreach $obj as $v}{$v}{/foreach}', ['obj' => new Record]),
		Latte\SecurityViolationException::class,
		"Access to 'secret' property on a Record object is not allowed.",
	);
	Assert::exception(
		fn() => $latte->renderToString('{foreach $obj as &$v}{$v}{/foreach}', ['obj' => new Record]),
		Latte\SecurityViolationException::class,
		"Access to 'secret' property on a Record object is not allowed.",
	);

	$policy->allowProperties(stdClass::class, $policy::All);
	Assert::same('12', $latte->renderToString('{foreach $obj as $v}{$v}{/foreach}', ['obj' => (object) ['a' => 1, 'b' => 2]]));
	Assert::same('12', $latte->renderToString('{foreach $obj as $v}{$v}{/foreach}', ['obj' => new ArrayIterator([1, 2])]));
});


test('foreach by reference still writes into the array', function () use ($latte) {
	Assert::same(
		'1020',
		$latte->renderToString('{var $arr = [1, 2]}{foreach $arr as &$v}{do $v *= 10}{/foreach}{$arr[0]}{$arr[1]}'),
	);
});
