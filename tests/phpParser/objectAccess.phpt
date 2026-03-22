<?php declare(strict_types=1);

// Object access

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* property fetch variations */
	$a->b,
	$a->b['c'],

	/* method call variations */
	$a->b(),
	$a->{'b'}(),
	$a->$b(),
	$a->$b['c'](),

	/* array dereferencing */
	$a->b()['c'],
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (7)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:5+1
   |  |  |  nullsafe: false
   |  |  |  position: 2:1+5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+5
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 3:1+2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 3:5+1
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 3:1+5
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'c'
   |  |  |  |  position: 3:7+3
   |  |  |  position: 3:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+10
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 6:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 6:5+1
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 6:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+7
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 7:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'b'
   |  |  |  |  position: 7:6+3
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 7:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+11
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 8:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 8:5+2
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 8:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1+8
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 9:1+2
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 9:5+2
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 9:1+6
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'c'
   |  |  |  |  |  position: 9:8+3
   |  |  |  |  position: 9:1+11
   |  |  |  args: array (0)
   |  |  |  position: 9:1+13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1+13
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 12:1+2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 12:5+1
   |  |  |  |  args: array (0)
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 12:1+7
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'c'
   |  |  |  |  position: 12:9+3
   |  |  |  position: 12:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1+12
   position: 2:1+136
