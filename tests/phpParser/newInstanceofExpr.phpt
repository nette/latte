<?php declare(strict_types=1);

// Arbitrary expressions in new and instanceof

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	new ('Foo' . $bar),
	new ('Foo' . $bar)($arg),
	$obj instanceof ('Foo' . $bar),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (3)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  position: 1:6+5
   |  |  |  |  operator: '.'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'bar'
   |  |  |  |  |  position: 1:14+4
   |  |  |  |  position: 1:6+12
   |  |  |  args: array (0)
   |  |  |  position: 1:1+18
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+18
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  position: 2:6+5
   |  |  |  |  operator: '.'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'bar'
   |  |  |  |  |  position: 2:14+4
   |  |  |  |  position: 2:6+12
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'arg'
   |  |  |  |  |  |  position: 2:20+4
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:20+4
   |  |  |  position: 2:1+24
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+24
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\InstanceofNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'obj'
   |  |  |  |  position: 3:1+4
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  position: 3:18+5
   |  |  |  |  operator: '.'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'bar'
   |  |  |  |  |  position: 3:26+4
   |  |  |  |  position: 3:18+12
   |  |  |  position: 3:1+30
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+30
   position: 1:1+77
