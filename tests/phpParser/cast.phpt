<?php declare(strict_types=1);

// Casts

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	(array)   $a,
	(bool)    $a,
	(float)   $a,
	(int)     $a,
	(object)  $a,
	(string)  $a,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (6)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\CastNode
   |  |  |  type: 'array'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:11+2
   |  |  |  position: 1:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+12
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\CastNode
   |  |  |  type: 'bool'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:11+2
   |  |  |  position: 2:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+12
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\CastNode
   |  |  |  type: 'float'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 3:11+2
   |  |  |  position: 3:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+12
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\CastNode
   |  |  |  type: 'int'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 4:11+2
   |  |  |  position: 4:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+12
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\CastNode
   |  |  |  type: 'object'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:11+2
   |  |  |  position: 5:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+12
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\CastNode
   |  |  |  type: 'string'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:11+2
   |  |  |  position: 6:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+12
   position: 1:1+83
