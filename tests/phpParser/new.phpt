<?php declare(strict_types=1);

// New

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	new A,
	new A($b),

	/* class name variations */
	new $a(),
	new $a['b'](),
	new A::$b(),
	/* DNCR object access */
	new $a->b(),
	new $a->b->c(),
	new $a->b['c'](),

	/* UVS new expressions */
	new $className,
	new $array['className'],
	new $obj->className,
	new Test::$className,
	new $test::$className,
	new $weird[0]->foo::$className,

	/* New dereference without parentheses */
	new A()->foo,
	new A()->foo(),
	new A()::FOO,
	new A()::foo(),
	new A()::$foo,
	new A()[0],
	new A()(),

	/* test regression introduces by new dereferencing syntax */
	(new A),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (22)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 1:5+1
   |  |  |  args: array (0)
   |  |  |  position: 1:1+5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+5
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 2:5+1
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:7+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:7+2
   |  |  |  position: 2:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+9
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:5+2
   |  |  |  args: array (0)
   |  |  |  position: 5:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+8
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 6:5+2
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  position: 6:8+3
   |  |  |  |  position: 6:5+7
   |  |  |  args: array (0)
   |  |  |  position: 6:1+13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+13
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 7:5+1
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 7:8+2
   |  |  |  |  position: 7:5+5
   |  |  |  args: array (0)
   |  |  |  position: 7:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+11
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 9:5+2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 9:9+1
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 9:5+5
   |  |  |  args: array (0)
   |  |  |  position: 9:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1+11
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 10:5+2
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 10:9+1
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 10:5+5
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 10:12+1
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 10:5+8
   |  |  |  args: array (0)
   |  |  |  position: 10:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1+14
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 11:5+2
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 11:9+1
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 11:5+5
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'c'
   |  |  |  |  |  position: 11:11+3
   |  |  |  |  position: 11:5+10
   |  |  |  args: array (0)
   |  |  |  position: 11:1+16
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1+16
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'className'
   |  |  |  |  position: 14:5+10
   |  |  |  args: array (0)
   |  |  |  position: 14:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1+14
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'array'
   |  |  |  |  |  position: 15:5+6
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'className'
   |  |  |  |  |  position: 15:12+11
   |  |  |  |  position: 15:5+19
   |  |  |  args: array (0)
   |  |  |  position: 15:1+23
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1+23
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'obj'
   |  |  |  |  |  position: 16:5+4
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  position: 16:11+9
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 16:5+15
   |  |  |  args: array (0)
   |  |  |  position: 16:1+19
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1+19
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'Test'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 17:5+4
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  position: 17:11+10
   |  |  |  |  position: 17:5+16
   |  |  |  args: array (0)
   |  |  |  position: 17:1+20
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1+20
   |  12 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'test'
   |  |  |  |  |  position: 18:5+5
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  position: 18:12+10
   |  |  |  |  position: 18:5+17
   |  |  |  args: array (0)
   |  |  |  position: 18:1+21
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 18:1+21
   |  13 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'weird'
   |  |  |  |  |  |  |  position: 19:5+6
   |  |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 19:12+1
   |  |  |  |  |  |  position: 19:5+9
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  position: 19:16+3
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 19:5+14
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  position: 19:21+10
   |  |  |  |  position: 19:5+26
   |  |  |  args: array (0)
   |  |  |  position: 19:1+30
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1+30
   |  14 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 22:5+1
   |  |  |  |  args: array (0)
   |  |  |  |  position: 22:1+7
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 22:10+3
   |  |  |  nullsafe: false
   |  |  |  position: 22:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 22:1+12
   |  15 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 23:5+1
   |  |  |  |  args: array (0)
   |  |  |  |  position: 23:1+7
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 23:10+3
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 23:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 23:1+14
   |  16 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 24:5+1
   |  |  |  |  args: array (0)
   |  |  |  |  position: 24:1+7
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'FOO'
   |  |  |  |  position: 24:10+3
   |  |  |  position: 24:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 24:1+12
   |  17 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 25:5+1
   |  |  |  |  args: array (0)
   |  |  |  |  position: 25:1+7
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 25:10+3
   |  |  |  args: array (0)
   |  |  |  position: 25:1+14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 25:1+14
   |  18 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 26:5+1
   |  |  |  |  args: array (0)
   |  |  |  |  position: 26:1+7
   |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 26:10+4
   |  |  |  position: 26:1+13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 26:1+13
   |  19 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 27:5+1
   |  |  |  |  args: array (0)
   |  |  |  |  position: 27:1+7
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 27:9+1
   |  |  |  position: 27:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 27:1+10
   |  20 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 28:5+1
   |  |  |  |  args: array (0)
   |  |  |  |  position: 28:1+7
   |  |  |  args: array (0)
   |  |  |  position: 28:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 28:1+9
   |  21 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 31:6+1
   |  |  |  args: array (0)
   |  |  |  position: 31:2+5
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 31:1+7
   position: 1:1+534
