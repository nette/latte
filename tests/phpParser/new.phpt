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
   |  |  |  |  position: 1:5
   |  |  |  |  end: 1:6
   |  |  |  args: array (0)
   |  |  |  position: 1:1
   |  |  |  end: 1:6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:6
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 2:5
   |  |  |  |  end: 2:6
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:7
   |  |  |  |  |  |  end: 2:9
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:7
   |  |  |  |  |  end: 2:9
   |  |  |  position: 2:1
   |  |  |  end: 2:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:10
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 5:5
   |  |  |  |  end: 5:7
   |  |  |  args: array (0)
   |  |  |  position: 5:1
   |  |  |  end: 5:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:9
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 6:5
   |  |  |  |  |  end: 6:7
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  position: 6:8
   |  |  |  |  |  end: 6:11
   |  |  |  |  position: 6:5
   |  |  |  |  end: 6:12
   |  |  |  args: array (0)
   |  |  |  position: 6:1
   |  |  |  end: 6:14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  |  end: 6:14
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 7:5
   |  |  |  |  |  end: 7:6
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 7:8
   |  |  |  |  |  end: 7:10
   |  |  |  |  position: 7:5
   |  |  |  |  end: 7:10
   |  |  |  args: array (0)
   |  |  |  position: 7:1
   |  |  |  end: 7:12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  |  end: 7:12
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 9:5
   |  |  |  |  |  end: 9:7
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 9:9
   |  |  |  |  |  end: 9:10
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 9:5
   |  |  |  |  end: 9:10
   |  |  |  args: array (0)
   |  |  |  position: 9:1
   |  |  |  end: 9:12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  |  end: 9:12
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 10:5
   |  |  |  |  |  |  end: 10:7
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 10:9
   |  |  |  |  |  |  end: 10:10
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 10:5
   |  |  |  |  |  end: 10:10
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  position: 10:12
   |  |  |  |  |  end: 10:13
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 10:5
   |  |  |  |  end: 10:13
   |  |  |  args: array (0)
   |  |  |  position: 10:1
   |  |  |  end: 10:15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  |  end: 10:15
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 11:5
   |  |  |  |  |  |  end: 11:7
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 11:9
   |  |  |  |  |  |  end: 11:10
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 11:5
   |  |  |  |  |  end: 11:10
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'c'
   |  |  |  |  |  position: 11:11
   |  |  |  |  |  end: 11:14
   |  |  |  |  position: 11:5
   |  |  |  |  end: 11:15
   |  |  |  args: array (0)
   |  |  |  position: 11:1
   |  |  |  end: 11:17
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   |  |  end: 11:17
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'className'
   |  |  |  |  position: 14:5
   |  |  |  |  end: 14:15
   |  |  |  args: array (0)
   |  |  |  position: 14:1
   |  |  |  end: 14:15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 14:1
   |  |  end: 14:15
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'array'
   |  |  |  |  |  position: 15:5
   |  |  |  |  |  end: 15:11
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'className'
   |  |  |  |  |  position: 15:12
   |  |  |  |  |  end: 15:23
   |  |  |  |  position: 15:5
   |  |  |  |  end: 15:24
   |  |  |  args: array (0)
   |  |  |  position: 15:1
   |  |  |  end: 15:24
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 15:1
   |  |  end: 15:24
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'obj'
   |  |  |  |  |  position: 16:5
   |  |  |  |  |  end: 16:9
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  position: 16:11
   |  |  |  |  |  end: 16:20
   |  |  |  |  nullsafe: false
   |  |  |  |  position: 16:5
   |  |  |  |  end: 16:20
   |  |  |  args: array (0)
   |  |  |  position: 16:1
   |  |  |  end: 16:20
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1
   |  |  end: 16:20
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'Test'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 17:5
   |  |  |  |  |  end: 17:9
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  position: 17:11
   |  |  |  |  |  end: 17:21
   |  |  |  |  position: 17:5
   |  |  |  |  end: 17:21
   |  |  |  args: array (0)
   |  |  |  position: 17:1
   |  |  |  end: 17:21
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 17:1
   |  |  end: 17:21
   |  12 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'test'
   |  |  |  |  |  position: 18:5
   |  |  |  |  |  end: 18:10
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  position: 18:12
   |  |  |  |  |  end: 18:22
   |  |  |  |  position: 18:5
   |  |  |  |  end: 18:22
   |  |  |  args: array (0)
   |  |  |  position: 18:1
   |  |  |  end: 18:22
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 18:1
   |  |  end: 18:22
   |  13 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'weird'
   |  |  |  |  |  |  |  position: 19:5
   |  |  |  |  |  |  |  end: 19:11
   |  |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  position: 19:12
   |  |  |  |  |  |  |  end: 19:13
   |  |  |  |  |  |  position: 19:5
   |  |  |  |  |  |  end: 19:14
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  position: 19:16
   |  |  |  |  |  |  end: 19:19
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 19:5
   |  |  |  |  |  end: 19:19
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  position: 19:21
   |  |  |  |  |  end: 19:31
   |  |  |  |  position: 19:5
   |  |  |  |  end: 19:31
   |  |  |  args: array (0)
   |  |  |  position: 19:1
   |  |  |  end: 19:31
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1
   |  |  end: 19:31
   |  14 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 22:5
   |  |  |  |  |  end: 22:6
   |  |  |  |  args: array (0)
   |  |  |  |  position: 22:1
   |  |  |  |  end: 22:8
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 22:10
   |  |  |  |  end: 22:13
   |  |  |  nullsafe: false
   |  |  |  position: 22:1
   |  |  |  end: 22:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 22:1
   |  |  end: 22:13
   |  15 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 23:5
   |  |  |  |  |  end: 23:6
   |  |  |  |  args: array (0)
   |  |  |  |  position: 23:1
   |  |  |  |  end: 23:8
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 23:10
   |  |  |  |  end: 23:13
   |  |  |  args: array (0)
   |  |  |  nullsafe: false
   |  |  |  position: 23:1
   |  |  |  end: 23:15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 23:1
   |  |  end: 23:15
   |  16 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ClassConstantFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 24:5
   |  |  |  |  |  end: 24:6
   |  |  |  |  args: array (0)
   |  |  |  |  position: 24:1
   |  |  |  |  end: 24:8
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'FOO'
   |  |  |  |  position: 24:10
   |  |  |  |  end: 24:13
   |  |  |  position: 24:1
   |  |  |  end: 24:13
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 24:1
   |  |  end: 24:13
   |  17 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticMethodCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 25:5
   |  |  |  |  |  end: 25:6
   |  |  |  |  args: array (0)
   |  |  |  |  position: 25:1
   |  |  |  |  end: 25:8
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 25:10
   |  |  |  |  end: 25:13
   |  |  |  args: array (0)
   |  |  |  position: 25:1
   |  |  |  end: 25:15
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 25:1
   |  |  end: 25:15
   |  18 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticPropertyFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 26:5
   |  |  |  |  |  end: 26:6
   |  |  |  |  args: array (0)
   |  |  |  |  position: 26:1
   |  |  |  |  end: 26:8
   |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 26:10
   |  |  |  |  end: 26:14
   |  |  |  position: 26:1
   |  |  |  end: 26:14
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 26:1
   |  |  end: 26:14
   |  19 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayAccessNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 27:5
   |  |  |  |  |  end: 27:6
   |  |  |  |  args: array (0)
   |  |  |  |  position: 27:1
   |  |  |  |  end: 27:8
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  position: 27:9
   |  |  |  |  end: 27:10
   |  |  |  position: 27:1
   |  |  |  end: 27:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 27:1
   |  |  end: 27:11
   |  20 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  kind: 1
   |  |  |  |  |  position: 28:5
   |  |  |  |  |  end: 28:6
   |  |  |  |  args: array (0)
   |  |  |  |  position: 28:1
   |  |  |  |  end: 28:8
   |  |  |  args: array (0)
   |  |  |  position: 28:1
   |  |  |  end: 28:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 28:1
   |  |  end: 28:10
   |  21 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'A'
   |  |  |  |  kind: 1
   |  |  |  |  position: 31:6
   |  |  |  |  end: 31:7
   |  |  |  args: array (0)
   |  |  |  position: 31:2
   |  |  |  end: 31:7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 31:1
   |  |  end: 31:8
   position: 1:1
   end: 31:9
