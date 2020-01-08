<?php

declare(strict_types=1);

use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->addFunction('Abc', function (stdClass $a, $b = 132) {});


$template = $latte->createTemplate('', ['int' => 123, 'unknown' => null]);

$printer = new Latte\Runtime\TemplatePrinter;
$class = $printer->print($template);

Assert::type(Nette\PhpGenerator\PhpNamespace::class, $class);

if (PHP_VERSION_ID >= 70400) {
	Assert::match(
'/**
 * @property int $int
 * @property mixed $unknown
 * @method mixed Abc(stdClass $a, $b = 132)
 */
class Template
{
	public int $int;

	public $unknown;
}',
		(string) $class
	);

} else {
	Assert::match(
'/**
 * @property int $int
 * @property mixed $unknown
 * @method mixed Abc(stdClass $a, $b = 132)
 */
class Template
{
	/** @var int */
	public $int;

	/** @var mixed */
	public $unknown;
}',
		(string) $class
	);
}
