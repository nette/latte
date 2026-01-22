<?php declare(strict_types=1);

/**
 * PHPStan type tests for custom extensions.
 */

use Latte\Extension;
use Latte\Runtime\FilterInfo;
use function PHPStan\Testing\assertType;


final class MyExtension extends Extension
{
	public function getFunctions(): array
	{
		return [
			'add' => fn(int $a, int $b): int => $a + $b,
			'greet' => fn(string $name, string $greeting = 'Hello'): string => "$greeting, $name!",
		];
	}


	public function getFilters(): array
	{
		return [
			'money' => fn(FilterInfo $info, float $amount, string $currency = 'EUR'): string => number_format($amount, 2) . ' ' . $currency,
		];
	}
}


function testExtensionMethods(Extension $ext): void
{
	assertType('array<string, callable(): mixed>', $ext->getFunctions());
	assertType('array<string, callable(): mixed>', $ext->getFilters());
}
