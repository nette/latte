<?php

// Reserved identifiers

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	__FILE__,
	class,
	yield (1),
	class(),
	class::$x,
	class::interface,
	$obj->interface,

	Foo::class,
	XX;

$node = parseCode($test);
$code = printNode($node);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	$code,
);

__halt_compiler();
namespace\__FILE__,
'class',
namespace\yield(1),
namespace\class(),
namespace\class::$x,
namespace\class::interface,
$obj->interface,
Foo::class
