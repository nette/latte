<?php

/**
 * Test: sandbox policy rules are part of the template cache key.
 */

declare(strict_types=1);

use Latte\Sandbox\SecurityPolicy;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function templateClass(?SecurityPolicy $policy): string
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader(['main' => '{=1}']));
	if ($policy) {
		$latte->setPolicy($policy);
		$latte->setSandboxMode();
	}
	return $latte->getTemplateClass('main');
}


test('same policy rules produce the same compiled class', function () {
	Assert::same(
		templateClass(SecurityPolicy::createSafePolicy()),
		templateClass(SecurityPolicy::createSafePolicy()),
	);
});


test('different policy rules produce different compiled classes', function () {
	$strict = SecurityPolicy::createSafePolicy();
	$loose = SecurityPolicy::createSafePolicy();
	$loose->allowFunctions(['system']);
	Assert::notSame(templateClass($strict), templateClass($loose));

	Assert::notSame(templateClass(null), templateClass(SecurityPolicy::createSafePolicy()));
});


test('internal lazy caches do not affect the signature', function () {
	$a = SecurityPolicy::createSafePolicy();
	$b = SecurityPolicy::createSafePolicy();
	$b->isMethodAllowed(Latte\Essential\CachingIterator::class, 'isFirst');
	$b->isPropertyAllowed(Latte\Essential\CachingIterator::class, 'counter');
	Assert::same(templateClass($a), templateClass($b));
});
