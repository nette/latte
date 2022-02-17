<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addFunction('Abc', function (stdClass $a, $b = 132) {});


$template = $latte->createTemplate('', ['int' => 123, 'unknown' => null]);

$printer = new Latte\Runtime\Blueprint;
ob_start();
$printer->printClass($template);
$res = ob_get_clean();

if (PHP_VERSION_ID >= 70400) {
	Assert::match(
		'%A%/**
 * @method mixed Abc(stdClass $a, $b = 132)
 */
class Template
{
	public int $int;
	public $unknown;
}
%A%',
		$res,
	);

} else {
	Assert::match(
		'%A%/**
 * @method mixed Abc(stdClass $a, $b = 132)
 */
class Template
{
	/** @var int */
	public $int;

	/** @var mixed */
	public $unknown;
}
%A%',
		$res,
	);
}
