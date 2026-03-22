<?php declare(strict_types=1);

// Comparison operators

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a < $b,
	$a <= $b,
	$a > $b,
	$a >= $b,
	$a == $b,
	$a === $b,
	$a != $b,
	$a !== $b,
	$a <=> $b,
	$a instanceof B,
	$a instanceof $b,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (11)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:1+2
   |  |  |  operator: '<'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 1:6+2
   |  |  |  position: 1:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+7
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:1+2
   |  |  |  operator: '<='
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:7+2
   |  |  |  position: 2:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+8
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 3:1+2
   |  |  |  operator: '>'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 3:6+2
   |  |  |  position: 3:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+7
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 4:1+2
   |  |  |  operator: '>='
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 4:7+2
   |  |  |  position: 4:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+8
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:1+2
   |  |  |  operator: '=='
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 5:7+2
   |  |  |  position: 5:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+8
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:1+2
   |  |  |  operator: '==='
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 6:8+2
   |  |  |  position: 6:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+9
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 7:1+2
   |  |  |  operator: '!='
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 7:7+2
   |  |  |  position: 7:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+8
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 8:1+2
   |  |  |  operator: '!=='
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 8:8+2
   |  |  |  position: 8:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1+9
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 9:1+2
   |  |  |  operator: '<=>'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 9:8+2
   |  |  |  position: 9:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1+9
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\InstanceofNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 10:1+2
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'B'
   |  |  |  |  kind: 1
   |  |  |  |  position: 10:15+1
   |  |  |  position: 10:1+15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1+15
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\InstanceofNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 11:1+2
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 11:15+2
   |  |  |  position: 11:1+16
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1+16
   position: 1:1+125
