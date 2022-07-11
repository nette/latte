<?php

/**
 * Test: {varType}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{varType}'),
	Latte\CompileException::class,
	'Missing arguments in {varType} (at column 1)',
);

Assert::exception(
	fn() => $latte->compile('{varType type}'),
	Latte\CompileException::class,
	'Unexpected end, expecting variable (at column 14)',
);

Assert::exception(
	fn() => $latte->compile('{varType type var}'),
	Latte\CompileException::class,
	'Unexpected end, expecting variable (at column 18)',
);

Assert::exception(
	fn() => $latte->compile('{varType $var type}'),
	Latte\CompileException::class,
	"Unexpected '\$vartype' (at column 10)",
);

Assert::contains(
	'/** @var type $var */',
	$latte->compile('{varType type $var}'),
);

Assert::contains(
	'/** @var ?\Nm\Class $var */',
	$latte->compile('{varType ?\Nm\Class $var}'),
);

Assert::contains(
	'/** @var int|null $var */',
	$latte->compile('{varType int|null $var}'),
);

Assert::contains(
	'/** @var array{0:int,1:int} $var */',
	$latte->compile('{varType array{0: int, 1: int} $var}'),
);

$template = <<<'XX'

{varType string $a}

{$a}

{varType string $c}
{var $c = 10}

{include test}

{define test}
  {varType int $b}
  {var $b = 5}
  {$a}{$b}
{/define}

XX;

Assert::matchFile(
	__DIR__ . '/expected/varType.phtml',
	$latte->compile($template),
);
