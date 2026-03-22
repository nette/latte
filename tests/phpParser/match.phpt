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
   |  |  |  |  position: 1:8+1
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 2:5+1
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  position: 2:10+5
   |  |  |  |  |  position: 2:5+10
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 3:5+1
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Bar'
   |  |  |  |  |  |  position: 3:10+5
   |  |  |  |  |  position: 3:5+10
   |  |  |  position: 1:1+45
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+45
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  position: 6:8+1
   |  |  |  arms: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 8:5+1
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 8:8+1
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  position: 8:13+5
   |  |  |  |  |  position: 8:5+13
   |  |  |  position: 6:1+61
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+61
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'operator'
   |  |  |  |  position: 11:8+9
   |  |  |  arms: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  name: 'BinaryOperator'
   |  |  |  |  |  |  |  |  kind: 1
   |  |  |  |  |  |  |  |  position: 12:5+14
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'ADD'
   |  |  |  |  |  |  |  |  position: 12:21+3
   |  |  |  |  |  |  |  position: 12:5+19
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'lhs'
   |  |  |  |  |  |  |  position: 12:28+4
   |  |  |  |  |  |  operator: '+'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'rhs'
   |  |  |  |  |  |  |  position: 12:35+4
   |  |  |  |  |  |  position: 12:28+11
   |  |  |  |  |  position: 12:5+34
   |  |  |  position: 11:1+61
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1+61
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'char'
   |  |  |  |  position: 15:8+5
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 16:5+1
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '1'
   |  |  |  |  |  |  position: 16:10+3
   |  |  |  |  |  position: 16:5+8
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: null
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'default'
   |  |  |  |  |  |  position: 17:16+9
   |  |  |  |  |  position: 17:5+20
   |  |  |  position: 15:1+56
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1+56
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  position: 20:8+1
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 21:5+1
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 21:8+1
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  position: 21:14+5
   |  |  |  |  |  position: 21:5+14
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: null
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Bar'
   |  |  |  |  |  |  position: 22:17+5
   |  |  |  |  |  position: 22:5+17
   |  |  |  position: 20:1+56
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 20:1+56
   position: 1:1+292
