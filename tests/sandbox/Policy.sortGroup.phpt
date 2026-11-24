<?php declare(strict_types=1);

/**
 * Test: |sort and |group read the property given by name only when the policy allows it.
 */

use Latte\Sandbox\SecurityPolicy;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Item
{
	public function __construct(
		public int $id,
		public string $name,
		public int $secret = 0,
	) {
	}
}


class Magic
{
	public bool $called = false;


	public function __get(string $name): mixed
	{
		$this->called = true;
		return 1;
	}
}


function renderSandboxed(string $template, array $params): string
{
	$latte = createLatte();
	$latte->setPolicy(SecurityPolicy::createSafePolicy()->allowFilters(['group'])->allowProperties(Item::class, ['id', 'name']));
	$latte->setSandboxMode();
	return $latte->renderToString($template, $params);
}


function render(string $template, array $params): string
{
	$result = renderSandboxed($template, $params);
	Assert::same(createLatte()->renderToString($template, $params), $result);
	return $result;
}


$items = [new Item(1, 'b'), new Item(2, 'a'), new Item(3, 'b')];


test('property not allowed by the policy', function () use ($items) {
	foreach ([
		'{foreach ($items|sort: by: secret) as $item}{/foreach}',
		'{foreach ($items|sort: null, secret) as $item}{/foreach}',
		'{foreach ($items|sort: ...$args) as $item}{/foreach}',
		'{foreach ($items|group: secret) as $item}{/foreach}',
		'{foreach ($items|group: by: secret) as $item}{/foreach}',
	] as $template) {
		Assert::exception(
			fn() => renderSandboxed($template, ['items' => $items, 'args' => ['by' => 'secret']]),
			Latte\SecurityViolationException::class,
			"Access to 'secret' property on a Item object is not allowed.",
		);
	}
});


test('property of an object key not allowed by the policy', function () use ($items) {
	$iterator = (function () use ($items) {
		foreach ($items as $item) {
			yield $item => 1;
		}
	})();
	Assert::exception(
		fn() => renderSandboxed('{foreach ($items|sort: byKey: secret) as $item}{/foreach}', ['items' => $iterator]),
		Latte\SecurityViolationException::class,
		"Access to 'secret' property on a Item object is not allowed.",
	);
});


test('__get is not called', function () {
	$items = [new Magic, new Magic];
	Assert::exception(
		fn() => renderSandboxed('{foreach ($items|sort: by: secret) as $item}{/foreach}', ['items' => $items]),
		Latte\SecurityViolationException::class,
		"Access to 'secret' property on a Magic object is not allowed.",
	);
	Assert::false($items[0]->called);
	Assert::false($items[1]->called);
});


test('sort', function () use ($items) {
	$arrays = [['id' => 1, 'name' => 'b'], ['id' => 2, 'name' => 'a'], ['id' => 3, 'name' => 'b']];
	$params = ['items' => $items, 'arrays' => $arrays, 'numbers' => [3, 1, 2], 'keys' => ['b' => 1, 'a' => 2]];
	Assert::same('213', render('{foreach ($items|sort: by: name) as $item}{$item->id}{/foreach}', $params));
	Assert::same('213', render('{foreach ($items|sort: null, name) as $item}{$item->id}{/foreach}', $params));
	Assert::same('132', render('{foreach ($items|sort: fn($a, $b) => $b <=> $a, name) as $item}{$item->id}{/foreach}', $params));
	Assert::same('213', render('{foreach ($items|sort: by: fn($item) => $item->name) as $item}{$item->id}{/foreach}', $params));
	Assert::same('213', render('{foreach ($arrays|sort: by: name) as $item}{$item[id]}{/foreach}', $params));
	Assert::same('123', render('{foreach ($numbers|sort) as $n}{$n}{/foreach}', $params));
	Assert::same('321', render('{foreach ($numbers|sort: fn($a, $b) => $b <=> $a) as $n}{$n}{/foreach}', $params));
	Assert::same('ab', render('{foreach ($keys|sort: byKey: true) as $k => $v}{$k}{/foreach}', $params));
});


test('group', function () use ($items) {
	$arrays = [['id' => 1, 'name' => 'b'], ['id' => 2, 'name' => 'a'], ['id' => 3, 'name' => 'b']];
	$params = ['items' => $items, 'arrays' => $arrays];
	$template = '{foreach $groups as $key => $group}{$key}:{foreach $group as $item}{$item->id}{/foreach} {/foreach}';
	Assert::same('b:13 a:2 ', render('{var $groups = ($items|group: name)}' . $template, $params));
	Assert::same('b:13 a:2 ', render('{var $groups = ($items|group: by: name)}' . $template, $params));
	Assert::same('b:13 a:2 ', render('{var $groups = ($items|group: fn($item) => $item->name)}' . $template, $params));
	Assert::same(
		'b:13 a:2 ',
		render(
			'{foreach ($arrays|group: name) as $key => $group}{$key}:{foreach $group as $item}{$item[id]}{/foreach} {/foreach}',
			$params,
		),
	);
});
