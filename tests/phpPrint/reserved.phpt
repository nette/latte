<?php declare(strict_types=1);

// Reserved identifiers

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	__FUNCTION__,
	class,
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
namespace\__FUNCTION__,
'class',
namespace\class::$x,
namespace\class::interface,
$obj->interface,
Foo::class
