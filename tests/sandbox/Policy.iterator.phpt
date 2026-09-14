<?php declare(strict_types=1);

/**
 * Test: $iterator does not pass calls to the inner iterator past the policy.
 */

use Latte\Sandbox\SecurityPolicy;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class SecretIterator extends ArrayIterator
{
	public function secret(): string
	{
		return 'secret';
	}
}


$latte = createLatte();
$latte->setPolicy(SecurityPolicy::createSafePolicy());
$latte->setSandboxMode();

Assert::same(
	'1',
	$latte->renderToString('{foreach $items as $item}{$iterator->counter}{/foreach}', ['items' => new SecretIterator([1])]),
);

Assert::exception(
	fn() => $latte->renderToString('{foreach $items as $item}{$iterator->secret()}{/foreach}', ['items' => new SecretIterator([1])]),
	Error::class,
	'Call to undefined method Latte\Essential\CachingIterator::secret()',
);
