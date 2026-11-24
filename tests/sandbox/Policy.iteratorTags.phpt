<?php declare(strict_types=1);

/**
 * Test: loop tags call methods only on the iterator of {foreach}, not on an object assigned to $iterator.
 */

use Latte\Sandbox\SecurityPolicy;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class FakeIterator
{
	public array $calls = [];


	public function isFirst(): bool
	{
		$this->calls[] = __FUNCTION__;
		return true;
	}


	public function isLast(): bool
	{
		$this->calls[] = __FUNCTION__;
		return true;
	}


	public function isEmpty(): bool
	{
		$this->calls[] = __FUNCTION__;
		return true;
	}


	public function hasNext(): bool
	{
		$this->calls[] = __FUNCTION__;
		return true;
	}


	public function next(): void
	{
		$this->calls[] = __FUNCTION__;
	}


	public function key(): int
	{
		$this->calls[] = __FUNCTION__;
		return 0;
	}


	public function current(): int
	{
		$this->calls[] = __FUNCTION__;
		return 0;
	}


	public function skipRound(): void
	{
		$this->calls[] = __FUNCTION__;
	}
}


$latte = createLatte();
$latte->setPolicy(SecurityPolicy::createSafePolicy());
$latte->setSandboxMode();


test('object assigned to $iterator is refused', function () use ($latte) {
	$templates = [
		'first' => '{foreach [1, 2] as $i}{var $iterator = $obj}{first}x{/first}{/foreach}',
		'last' => '{foreach [1, 2] as $i}{var $iterator = $obj}{last}x{/last}{/foreach}',
		'sep' => '{foreach [1, 2] as $i}{var $iterator = $obj}{sep}x{/sep}{/foreach}',
		'skipIf' => '{foreach [1, 2] as $i}{var $iterator = $obj}{skipIf true}{/foreach}',
		'iterateWhile' => '{foreach [1, 2] as $i}{var $iterator = $obj}{iterateWhile true}{/iterateWhile}{/foreach}',
		'iterateWhile post-test' => '{foreach [1, 2] as $i}{var $iterator = $obj}{iterateWhile}{/iterateWhile true}{/foreach}',
		'iterateWhile condition' => '{foreach [1, 2] as $i}{iterateWhile ($iterator = $obj) || true}{/iterateWhile}{/foreach}',
		'iterateWhile post-test condition' => '{foreach [1, 2] as $i}{iterateWhile}{/iterateWhile ($iterator = $obj) || true}{/foreach}',
		'else' => '{foreach [1, 2] as $i}{var $iterator = $obj}{else}x{/foreach}',
	];

	foreach ($templates as $name => $template) {
		$obj = new FakeIterator;
		Assert::exception(
			fn() => $latte->renderToString($template, ['obj' => $obj]),
			Latte\SecurityViolationException::class,
			'Variable $iterator is not the iterator of {foreach}.',
		);
		Assert::same([], $obj->calls, $name);
	}
});


test('loop tags work in the sandbox', function () use ($latte) {
	Assert::same(
		'[1,2,3]',
		$latte->renderToString('{foreach [1, 2, 3] as $i}{first}[{/first}{$i}{sep},{/sep}{last}]{/last}{/foreach}'),
	);

	Assert::same(
		'13',
		$latte->renderToString('{foreach [1, 2, 3] as $i}{skipIf $i === 2}{$i}{/foreach}'),
	);

	Assert::same(
		'(1 2 )(3)',
		$latte->renderToString('{foreach [1, 2, 3] as $i}({iterateWhile}{$i}{sep} {/sep}{/iterateWhile $i < 2}){/foreach}'),
	);

	Assert::same(
		'(12)(3)',
		$latte->renderToString('{foreach [1, 2, 3] as $i}({$i}{iterateWhile $i < 2}{$i}{/iterateWhile}){/foreach}'),
	);

	Assert::same(
		'empty',
		$latte->renderToString('{foreach [] as $i}{$i}{else}empty{/foreach}'),
	);

	Assert::same(
		'1',
		$latte->renderToString('{foreach [1] as $i}{$i}{else}empty{/foreach}'),
	);

	Assert::same(
		'a1,a2;b1,b2',
		$latte->renderToString('{foreach [a, b] as $x}{foreach [1, 2] as $i}{$x}{$i}{sep},{/sep}{/foreach}{sep};{/sep}{/foreach}'),
	);

	Assert::same(
		'[1][2]',
		$latte->renderToString('{foreach [1, 2] as $i}{block item}{first}[1]{else}[{$i}]{/first}{/block}{/foreach}'),
	);
});


test('{sandbox} checks $iterator', function () {
	$latte = createLatte();
	$latte->setPolicy(SecurityPolicy::createSafePolicy());
	$latte->setLoader(new Latte\Loaders\StringLoader([
		'main' => '{foreach [1, 2] as $i}{first}[{/first}{$i}{/foreach} {sandbox inner.latte, obj: $obj}',
		'inner.latte' => '{foreach [1, 2] as $i}{var $iterator = $obj}{first}x{/first}{/foreach}',
	]));
	$obj = new FakeIterator;
	Assert::exception(
		fn() => $latte->renderToString('main', ['obj' => $obj]),
		Latte\SecurityViolationException::class,
		'Variable $iterator is not the iterator of {foreach}.',
	);
	Assert::same([], $obj->calls);
});
