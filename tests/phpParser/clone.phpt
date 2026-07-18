<?php declare(strict_types=1);

// Clone

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	clone $x,
	clone($x),
	clone($x, ),
	clone($x, [ 'foo' => $foo, 'bar' => $bar ]),
	clone($x, $array),
	clone($x, $array, $extraParameter, $trailingComma, ),
	clone(object: $x, withProperties: [ 'foo' => $foo, 'bar' => $bar ]),
	clone($x, withProperties: [ 'foo' => $foo, 'bar' => $bar ]),
	clone(object: $x),
	clone(object: $x, [ 'foo' => $foo, 'bar' => $bar ]),
	clone(...['object' => $x, 'withProperties' => [ 'foo' => $foo, 'bar' => $bar ]]),
	clone(...),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (12)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\CloneNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 1:7
   |  |  |  |  end: 1:9
   |  |  |  position: 1:1
   |  |  |  end: 1:9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:9
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\CloneNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  position: 2:7
   |  |  |  |  end: 2:9
   |  |  |  position: 2:1
   |  |  |  end: 2:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:10
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 3:1
   |  |  |  |  end: 3:12
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 3:7
   |  |  |  |  |  |  end: 3:9
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 3:6
   |  |  |  |  |  end: 3:12
   |  |  |  position: 3:1
   |  |  |  end: 3:12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:12
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 4:1
   |  |  |  |  end: 4:44
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 4:7
   |  |  |  |  |  |  end: 4:9
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 4:7
   |  |  |  |  |  end: 4:43
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  |  |  position: 4:22
   |  |  |  |  |  |  |  |  |  end: 4:26
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  position: 4:13
   |  |  |  |  |  |  |  |  |  end: 4:18
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 4:13
   |  |  |  |  |  |  |  |  end: 4:26
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  |  |  |  position: 4:37
   |  |  |  |  |  |  |  |  |  end: 4:41
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'bar'
   |  |  |  |  |  |  |  |  |  position: 4:28
   |  |  |  |  |  |  |  |  |  end: 4:33
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 4:28
   |  |  |  |  |  |  |  |  end: 4:41
   |  |  |  |  |  |  position: 4:11
   |  |  |  |  |  |  end: 4:43
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 4:11
   |  |  |  |  |  end: 4:43
   |  |  |  position: 4:1
   |  |  |  end: 4:44
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:44
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 5:1
   |  |  |  |  end: 5:18
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 5:7
   |  |  |  |  |  |  end: 5:9
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 5:7
   |  |  |  |  |  end: 5:17
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 5:11
   |  |  |  |  |  |  end: 5:17
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 5:11
   |  |  |  |  |  end: 5:17
   |  |  |  position: 5:1
   |  |  |  end: 5:18
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:18
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 6:1
   |  |  |  |  end: 6:53
   |  |  |  args: array (4)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 6:7
   |  |  |  |  |  |  end: 6:9
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 6:7
   |  |  |  |  |  end: 6:17
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  position: 6:11
   |  |  |  |  |  |  end: 6:17
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 6:11
   |  |  |  |  |  end: 6:17
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'extraParameter'
   |  |  |  |  |  |  position: 6:19
   |  |  |  |  |  |  end: 6:34
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 6:19
   |  |  |  |  |  end: 6:34
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'trailingComma'
   |  |  |  |  |  |  position: 6:36
   |  |  |  |  |  |  end: 6:50
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 6:36
   |  |  |  |  |  end: 6:50
   |  |  |  position: 6:1
   |  |  |  end: 6:53
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1
   |  |  end: 6:53
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 7:1
   |  |  |  |  end: 7:68
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 7:15
   |  |  |  |  |  |  end: 7:17
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'object'
   |  |  |  |  |  |  position: 7:7
   |  |  |  |  |  |  end: 7:13
   |  |  |  |  |  position: 7:7
   |  |  |  |  |  end: 7:17
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  |  |  position: 7:46
   |  |  |  |  |  |  |  |  |  end: 7:50
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  position: 7:37
   |  |  |  |  |  |  |  |  |  end: 7:42
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 7:37
   |  |  |  |  |  |  |  |  end: 7:50
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  |  |  |  position: 7:61
   |  |  |  |  |  |  |  |  |  end: 7:65
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'bar'
   |  |  |  |  |  |  |  |  |  position: 7:52
   |  |  |  |  |  |  |  |  |  end: 7:57
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 7:52
   |  |  |  |  |  |  |  |  end: 7:65
   |  |  |  |  |  |  position: 7:35
   |  |  |  |  |  |  end: 7:67
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'withProperties'
   |  |  |  |  |  |  position: 7:19
   |  |  |  |  |  |  end: 7:33
   |  |  |  |  |  position: 7:19
   |  |  |  |  |  end: 7:67
   |  |  |  position: 7:1
   |  |  |  end: 7:68
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1
   |  |  end: 7:68
   |  7 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 8:1
   |  |  |  |  end: 8:60
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 8:7
   |  |  |  |  |  |  end: 8:9
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 8:7
   |  |  |  |  |  end: 8:59
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  |  |  position: 8:38
   |  |  |  |  |  |  |  |  |  end: 8:42
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  position: 8:29
   |  |  |  |  |  |  |  |  |  end: 8:34
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 8:29
   |  |  |  |  |  |  |  |  end: 8:42
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  |  |  |  position: 8:53
   |  |  |  |  |  |  |  |  |  end: 8:57
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'bar'
   |  |  |  |  |  |  |  |  |  position: 8:44
   |  |  |  |  |  |  |  |  |  end: 8:49
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 8:44
   |  |  |  |  |  |  |  |  end: 8:57
   |  |  |  |  |  |  position: 8:27
   |  |  |  |  |  |  end: 8:59
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'withProperties'
   |  |  |  |  |  |  position: 8:11
   |  |  |  |  |  |  end: 8:25
   |  |  |  |  |  position: 8:11
   |  |  |  |  |  end: 8:59
   |  |  |  position: 8:1
   |  |  |  end: 8:60
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1
   |  |  end: 8:60
   |  8 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 9:1
   |  |  |  |  end: 9:18
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 9:15
   |  |  |  |  |  |  end: 9:17
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'object'
   |  |  |  |  |  |  position: 9:7
   |  |  |  |  |  |  end: 9:13
   |  |  |  |  |  position: 9:7
   |  |  |  |  |  end: 9:17
   |  |  |  position: 9:1
   |  |  |  end: 9:18
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 9:1
   |  |  end: 9:18
   |  9 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 10:1
   |  |  |  |  end: 10:52
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  position: 10:15
   |  |  |  |  |  |  end: 10:17
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'object'
   |  |  |  |  |  |  position: 10:7
   |  |  |  |  |  |  end: 10:13
   |  |  |  |  |  position: 10:7
   |  |  |  |  |  end: 10:17
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  |  |  position: 10:30
   |  |  |  |  |  |  |  |  |  end: 10:34
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  position: 10:21
   |  |  |  |  |  |  |  |  |  end: 10:26
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 10:21
   |  |  |  |  |  |  |  |  end: 10:34
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  |  |  |  position: 10:45
   |  |  |  |  |  |  |  |  |  end: 10:49
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'bar'
   |  |  |  |  |  |  |  |  |  position: 10:36
   |  |  |  |  |  |  |  |  |  end: 10:41
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 10:36
   |  |  |  |  |  |  |  |  end: 10:49
   |  |  |  |  |  |  position: 10:19
   |  |  |  |  |  |  end: 10:51
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 10:19
   |  |  |  |  |  end: 10:51
   |  |  |  position: 10:1
   |  |  |  end: 10:52
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 10:1
   |  |  end: 10:52
   |  10 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 11:1
   |  |  |  |  end: 11:81
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  position: 11:23
   |  |  |  |  |  |  |  |  |  end: 11:25
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'object'
   |  |  |  |  |  |  |  |  |  position: 11:11
   |  |  |  |  |  |  |  |  |  end: 11:19
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 11:11
   |  |  |  |  |  |  |  |  end: 11:25
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\ArrayNode
   |  |  |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:58
   |  |  |  |  |  |  |  |  |  |  |  |  end: 11:62
   |  |  |  |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:49
   |  |  |  |  |  |  |  |  |  |  |  |  end: 11:54
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  position: 11:49
   |  |  |  |  |  |  |  |  |  |  |  end: 11:62
   |  |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:73
   |  |  |  |  |  |  |  |  |  |  |  |  end: 11:77
   |  |  |  |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'bar'
   |  |  |  |  |  |  |  |  |  |  |  |  position: 11:64
   |  |  |  |  |  |  |  |  |  |  |  |  end: 11:69
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  position: 11:64
   |  |  |  |  |  |  |  |  |  |  |  end: 11:77
   |  |  |  |  |  |  |  |  |  position: 11:47
   |  |  |  |  |  |  |  |  |  end: 11:79
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'withProperties'
   |  |  |  |  |  |  |  |  |  position: 11:27
   |  |  |  |  |  |  |  |  |  end: 11:43
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  position: 11:27
   |  |  |  |  |  |  |  |  end: 11:79
   |  |  |  |  |  |  position: 11:10
   |  |  |  |  |  |  end: 11:80
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: true
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 11:7
   |  |  |  |  |  end: 11:80
   |  |  |  position: 11:1
   |  |  |  end: 11:81
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1
   |  |  end: 11:81
   |  11 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  name: 'clone'
   |  |  |  |  kind: 1
   |  |  |  |  position: 12:1
   |  |  |  |  end: 12:11
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\VariadicPlaceholderNode
   |  |  |  |  |  position: 12:7
   |  |  |  |  |  end: 12:10
   |  |  |  position: 12:1
   |  |  |  end: 12:11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 12:1
   |  |  end: 12:11
   position: 1:1
   end: 12:12
