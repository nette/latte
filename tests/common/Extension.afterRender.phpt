<?php

/**
 * Test: Extension::afterRender() is called even on early exit or exception.
 */

declare(strict_types=1);

use Latte\Runtime\Template;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class LoggingExtension extends Latte\Extension
{
	public array $log = [];


	public function beforeRender(Template $template): void
	{
		$this->log[] = 'before ' . $template->getName();
	}


	public function afterRender(Template $template): void
	{
		$this->log[] = 'after ' . $template->getName();
	}
}


function createEngine(array $templates): array
{
	$latte = createLatte();
	$latte->setLoader(new Latte\Loaders\StringLoader($templates));
	$latte->addExtension($ext = new LoggingExtension);
	return [$latte, $ext];
}


test('normal rendering', function () {
	[$latte, $ext] = createEngine(['main' => 'hello']);
	Assert::same('hello', $latte->renderToString('main'));
	Assert::same(['before main', 'after main'], $ext->log);
});


test('{exitIf} early exit still calls afterRender', function () {
	[$latte, $ext] = createEngine(['main' => 'a{exitIf true}b']);
	Assert::same('a', $latte->renderToString('main'));
	Assert::same(['before main', 'after main'], $ext->log);
});


test('exception during rendering still calls afterRender', function () {
	[$latte, $ext] = createEngine(['main' => '{$x|boom}']);
	$latte->addFilter('boom', fn() => throw new LogicException('boom'));
	Assert::exception(
		fn() => $latte->renderToString('main', ['x' => 1]),
		LogicException::class,
		'boom',
	);
	Assert::same(['before main', 'after main'], $ext->log);
});


test('exception in a later beforeRender still calls afterRender of earlier extensions', function () {
	[$latte, $ext] = createEngine(['main' => 'hello']);
	$latte->addExtension(new class extends Latte\Extension {
		public function beforeRender(Template $template): void
		{
			throw new RuntimeException('boom');
		}
	});
	Assert::exception(
		fn() => $latte->renderToString('main'),
		RuntimeException::class,
		'boom',
	);
	Assert::same(['before main', 'after main'], $ext->log);
});


test('{include} and {extends} report each template', function () {
	[$latte, $ext] = createEngine([
		'main' => '{extends parent}{block content}c{/block}',
		'parent' => '{include file inc}{include content}',
		'inc' => 'i',
	]);
	Assert::same('ic', $latte->renderToString('main'));
	Assert::same([
		'before main',
		'before parent',
		'before inc',
		'after inc',
		'after parent',
		'after main',
	], $ext->log);
});
