<?php declare(strict_types=1);

// Match

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	match (1) {
	    0 => 'Foo',
	    1 => 'Bar',
	},

	match (1) {
	    /* list of conditions */
	    0, 1 => 'Foo',
	},

	match ($operator) {
	    BinaryOperator::ADD => $lhs + $rhs,
	},

	match ($char) {
	    1 => '1',
	    default => 'default'
	},

	match (1) {
	    0, 1, => 'Foo',
	    default, => 'Bar',
	},
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (5)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  position: 1:8
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 2:5
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  position: 2:10
   |  |  |  |  |  position: 2:5
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 3:5
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Bar'
   |  |  |  |  |  |  position: 3:10
   |  |  |  |  |  position: 3:5
   |  |  |  position: 1:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  position: 6:8
   |  |  |  arms: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 8:5
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 8:8
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  position: 8:13
   |  |  |  |  |  position: 8:5
   |  |  |  position: 6:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'operator'
   |  |  |  |  position: 11:8
   |  |  |  arms: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'BinaryOperator'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 12:5
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'ADD'
   |  |  |  |  |  |  |  |  position: 12:21
   |  |  |  |  |  |  |  position: 12:5
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'lhs'
   |  |  |  |  |  |  |  position: 12:28
   |  |  |  |  |  |  operator: '+'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'rhs'
   |  |  |  |  |  |  |  position: 12:35
   |  |  |  |  |  |  position: 12:28
   |  |  |  |  |  position: 12:5
   |  |  |  position: 11:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'char'
   |  |  |  |  position: 15:8
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 16:5
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '1'
   |  |  |  |  |  |  position: 16:10
   |  |  |  |  |  position: 16:5
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: null
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'default'
   |  |  |  |  |  |  position: 17:16
   |  |  |  |  |  position: 17:5
   |  |  |  position: 15:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  position: 20:8
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 21:5
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 21:8
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  position: 21:14
   |  |  |  |  |  position: 21:5
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: null
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Bar'
   |  |  |  |  |  |  position: 22:17
   |  |  |  |  |  position: 22:5
   |  |  |  position: 20:1
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 20:1
   position: 1:1
