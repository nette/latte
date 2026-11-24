<?php declare(strict_types=1);

/**
 * Test: the {foreach} target assigned by {iterateWhile} is checked by the policy.
 */

use Latte\Sandbox\SecurityPolicy;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Allowed
{
	public $prop;
}

class Forbidden
{
	public $prop = 'original';
}


$latte = createLatte();
$latte->setPolicy(SecurityPolicy::createSafePolicy()->allowProperties(Allowed::class, ['prop']));
$latte->setSandboxMode();

$templates = [
	'value' => '{foreach [1, 2] as $obj->prop}{do $obj = $forbidden}{iterateWhile true}{/iterateWhile}{/foreach}',
	'key' => '{foreach [1, 2] as $obj->prop => $v}{do $obj = $forbidden}{iterateWhile true}{/iterateWhile}{/foreach}',
	'post-test' => '{foreach [1, 2] as $obj->prop}{do $obj = $forbidden}{iterateWhile}{/iterateWhile true}{/foreach}',
];

foreach ($templates as $name => $template) {
	$forbidden = new Forbidden;
	Assert::exception(
		fn() => $latte->renderToString($template, ['obj' => new Allowed, 'forbidden' => $forbidden]),
		Latte\SecurityViolationException::class,
		"Access to 'prop' property on a Forbidden object is not allowed.",
	);
	Assert::same('original', $forbidden->prop, $name);
}


Assert::same(
	'1 2 ',
	$latte->renderToString(
		'{foreach [1, 2] as $obj->prop}{iterateWhile}{$obj->prop} {/iterateWhile true}{/foreach}',
		['obj' => new Allowed],
	),
);
