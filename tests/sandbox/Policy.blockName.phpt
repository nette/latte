<?php declare(strict_types=1);

/**
 * Test: the name of a dynamic block is checked by the policy.
 */

use Latte\Sandbox\SecurityPolicy;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Record
{
	public static bool $called = false;


	public function method(): string
	{
		self::$called = true;
		return 'name';
	}
}


$latte = createLatte();
$latte->setPolicy(SecurityPolicy::createSafePolicy());
$latte->setSandboxMode();

$templates = [
	'{block $obj->method()}x{/block}',
	'{define $obj->method()}x{/define}',
	'<div n:block="$obj->method()">x</div>',
];

foreach ($templates as $template) {
	Record::$called = false;
	Assert::exception(
		fn() => $latte->renderToString($template, ['obj' => new Record]),
		Latte\SecurityViolationException::class,
		'Calling Record::method() is not allowed.',
	);
	Assert::false(Record::$called, $template);
}
