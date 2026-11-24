<?php declare(strict_types=1);

/**
 * Test: the safe policy does not allow filters that read all public properties of an object.
 */

use Latte\Sandbox\SecurityPolicy;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = createLatte();
$latte->setPolicy(SecurityPolicy::createSafePolicy());
$latte->setSandboxMode();

Assert::exception(
	fn() => $latte->renderToString('{$x|query}', ['x' => ['a' => 1]]),
	Latte\SecurityViolationException::class,
	'Filter |query is not allowed (on line 1 at column 4)',
);

Assert::exception(
	fn() => $latte->renderToString('{$x|escapeJs}', ['x' => 'a']),
	Latte\SecurityViolationException::class,
	'Filter |escapeJs is not allowed (on line 1 at column 4)',
);
