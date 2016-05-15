<?php

/**
 * Test: {include file}
 */

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class NoPolicy implements Latte\Policy
{
	public function isMacroAllowed(string $macro): bool
	{
		return $macro === '=';
	}


	public function isFilterAllowed(string $filter): bool
	{
		return false;
	}


	public function isFunctionAllowed(string $function): bool
	{
		return false;
	}


	public function isMethodAllowed(string $class, string $method): bool
	{
		return false;
	}


	public function isPropertyAllowed(string $class, string $property): bool
	{
		return false;
	}
}


$latte = new Latte\Engine;
$latte->setPolicy(new NoPolicy);
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => 'before {sandbox inc1.latte var => 1} after',
	'main2' => 'before {sandbox inc2.latte} after',
	'main3' => 'before {sandbox inc3.latte} after',

	'inc1.latte' => '<b>included {$var}</b>',
	'inc2.latte' => '<b>{var $var}</b>',
	'inc3.latte' => '<b>{$obj->item}</b>',
]));

Assert::match(
	'before <b>included 1</b> after',
	$latte->renderToString('main1')
);

Assert::exception(function () use ($latte) {
	$latte->renderToString('main2');
}, Latte\CompileException::class, 'Macro {var} is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('main3', ['obj' => new stdClass]);
}, Latte\SecurityViolation::class, "Access to 'item' property on a stdClass object is not allowed.");
