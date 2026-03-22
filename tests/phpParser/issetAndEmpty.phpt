<?php declare(strict_types=1);

// isset() and empty()

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	isset($a),
	isset($a, $b, $c),

	empty($a),
	empty(foo()),
	empty(array(1, 2, 3)),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (5)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\IssetNode
   |  |  |  vars: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 1:7+2
   |  |  |  position: 1:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+9
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\IssetNode
   |  |  |  vars: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 2:7+2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 2:11+2
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 2:15+2
   |  |  |  position: 2:1+17
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+17
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\EmptyNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 4:7+2
   |  |  |  position: 4:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+9
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\EmptyNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'foo'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 5:7+3
   |  |  |  |  args: array (0)
   |  |  |  |  position: 5:7+5
   |  |  |  position: 5:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+12
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\EmptyNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 6:13+1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 6:13+1
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 6:16+1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 6:16+1
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 6:19+1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  position: 6:19+1
   |  |  |  |  position: 6:7+14
   |  |  |  position: 6:1+21
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+21
   position: 1:1+78
