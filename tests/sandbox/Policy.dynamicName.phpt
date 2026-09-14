<?php declare(strict_types=1);

/**
 * Test: a dynamic member name is checked and used as the same value.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Record
{
	public static $staticVisible = 'visible';
	public static $staticSecret = 'SECRET';
	public $visible = 'visible';
	public $secret = 'SECRET';
}


$policy = new Latte\Sandbox\SecurityPolicy;
$policy->allowTags($policy::All);
$policy->allowProperties(Record::class, ['visible', 'staticVisible']);
$latte = createLatte();
$latte->setPolicy($policy);
$latte->setSandboxMode();


test('name is evaluated once', function () use ($latte) {
	Assert::same(
		'visible',
		$latte->renderToString('{var $i = 0}{$obj->{["visible", "secret"][$i++]}}', ['obj' => new Record]),
	);
	Assert::same(
		'visible',
		$latte->renderToString('{var $i = 0}{=Record::${["staticVisible", "staticSecret"][$i++]}}'),
	);
});


test('name changed by the assigned value does not redirect the write', function () use ($latte) {
	$obj = new Record;
	$latte->renderToString('{var $n = "visible"}{do $obj->$n = ($n = "secret")}', ['obj' => $obj]);
	Assert::same('secret', $obj->visible);
	Assert::same('SECRET', $obj->secret);
});
