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
	'main1' => 'before {sandbox inc1.latte} after',
	'main2' => 'before {sandbox inc1.latte, var => 1} after',
	'main3' => 'before {sandbox inc2.latte} after',
	'main4' => 'before {sandbox inc3.latte, obj => new stdClass} after',

	'inc1.latte' => '<b>included {$var}</b>',
	'inc2.latte' => '<b>{var $var}</b>',
	'inc3.latte' => '<b>{$obj->item}</b>',
]));


Assert::error(function () use ($latte) {
	$latte->renderToString('main1');
}, E_NOTICE, 'Undefined variable: var');

Assert::error(function () use ($latte) {
	$latte->renderToString('main1', ['var' => 123]);
}, E_NOTICE, 'Undefined variable: var');

Assert::match(
	'before <b>included 1</b> after',
	$latte->renderToString('main2')
);

Assert::exception(function () use ($latte) {
	$latte->renderToString('main3');
}, Latte\CompileException::class, 'Macro {var} is not allowed.');

Assert::exception(function () use ($latte) {
	$latte->renderToString('main4');
}, Latte\SecurityViolation::class, "Access to 'item' property on a stdClass object is not allowed.");
