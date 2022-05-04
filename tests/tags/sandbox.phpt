<?php

/**
 * Test: {include file}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setPolicy((new Latte\Sandbox\SecurityPolicy)->allowTags(['=']));
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main1' => 'before {sandbox inc1.latte} after',
	'main2' => 'before {sandbox inc1.latte, var => 1} after',
	'main3' => 'before {sandbox inc2.latte} after',
	'main4' => 'before {sandbox inc3.latte, obj => new stdClass} after',

	'inc1.latte' => '<b>included {$var}</b>',
	'inc2.latte' => '<b>{var $var}</b>',
	'inc3.latte' => '<b>{$obj->item}</b>',
]));


Assert::error(
	fn() => $latte->renderToString('main1'),
	E_WARNING,
	'Undefined variable%a%var',
);

Assert::error(
	fn() => $latte->renderToString('main1', ['var' => 123]),
	E_WARNING,
	'Undefined variable%a%var',
);

Assert::match(
	'before <b>included 1</b> after',
	$latte->renderToString('main2'),
);

Assert::exception(
	fn() => $latte->renderToString('main3'),
	Latte\CompileException::class,
	'Tag {var} is not allowed.',
);

Assert::exception(
	fn() => $latte->renderToString('main4'),
	Latte\SecurityViolationException::class,
	"Access to 'item' property on a stdClass object is not allowed.",
);
