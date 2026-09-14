<?php declare(strict_types=1);

/**
 * Test: a policy without cache signature is reported.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class CustomPolicy implements Latte\Policy
{
	public function isTagAllowed(string $tag): bool
	{
		return true;
	}


	public function isFilterAllowed(string $filter): bool
	{
		return true;
	}


	public function isFunctionAllowed(string $function): bool
	{
		return true;
	}


	public function isMethodAllowed(string $class, string $method): bool
	{
		return true;
	}


	public function isPropertyAllowed(string $class, string $property): bool
	{
		return true;
	}
}


$latte = createLatte();
$latte->setPolicy(new CustomPolicy);
$latte->setSandboxMode();

Assert::error(
	fn() => $latte->getTemplateClass('{=1}'),
	E_USER_DEPRECATED,
	'CustomPolicy should implement getCacheSignature(), otherwise its instances with different rules share compiled templates.',
);
