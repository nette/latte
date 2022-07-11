<?php

/**
 * Test: {templateType}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class TemplateClass
{
	public $noType;
	public int $intType;
	public int|bool $intBoolType;
	/** @var array<int, string> */
	public array $arrayType;
	private int $private;
}


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{templateType}'),
	Latte\CompileException::class,
	'Missing class name in {templateType} (at column 1)',
);

Assert::exception(
	fn() => $latte->compile('{templateType AA\BBB}'),
	Latte\CompileException::class,
	"Class 'AA\\BBB' used in {templateType} doesn't exist (at column 15)",
);

Assert::exception(
	fn() => $latte->compile('{if true}{templateType stdClass}{/if}'),
	Latte\CompileException::class,
	'{templateType} is allowed only in template header (at column 10)',
);

Assert::contains(
	'/** @var int $intType */' . "\n\t\t" .
	'/** @var int|bool $intBoolType */'. "\n\t\t" .
	'/** @var array<int, string> $arrayType */'. "\n\n",
	$latte->compile('{templateType TemplateClass}{$intBoolType}'),
);

Assert::matchFile(
	__DIR__ . '/expected/templateType.phtml',
	$latte->compile('{templateType TemplateClass}{$intBoolType}{define test}{$intBoolType}{/define}')
);
