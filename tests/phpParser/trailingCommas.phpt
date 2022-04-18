<?php

// PHP 7.3 trailing comma additions

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	foo($a, $b, ),
	$foo->bar($a, $b, ),
	Foo::bar($a, $b, ),
	new Foo($a, $b, ),
	unset($a, $b, ),
	isset($a, $b, ),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (6)
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'foo'
   |  |  |  |  position: 1:1 (offset 0)
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 1:5 (offset 4)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 1:5 (offset 4)
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 1:9 (offset 8)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 1:9 (offset 8)
   |  |  |  |  |  name: null
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 1:1 (offset 0)
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'foo'
   |  |  |  |  position: 2:1 (offset 15)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'bar'
   |  |  |  |  position: 2:7 (offset 21)
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 2:11 (offset 25)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 2:11 (offset 25)
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 2:15 (offset 29)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 2:15 (offset 29)
   |  |  |  |  |  name: null
   |  |  |  position: 2:1 (offset 15)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 2:1 (offset 15)
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\StaticCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'Foo'
   |  |  |  |  position: 3:1 (offset 36)
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'bar'
   |  |  |  |  position: 3:6 (offset 41)
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:10 (offset 45)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 3:10 (offset 45)
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 3:14 (offset 49)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 3:14 (offset 49)
   |  |  |  |  |  name: null
   |  |  |  position: 3:1 (offset 36)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 3:1 (offset 36)
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'Foo'
   |  |  |  |  position: 4:5 (offset 60)
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 4:9 (offset 64)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 4:9 (offset 64)
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 4:13 (offset 68)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 4:13 (offset 68)
   |  |  |  |  |  name: null
   |  |  |  position: 4:1 (offset 56)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 4:1 (offset 56)
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\FunctionCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'unset'
   |  |  |  |  position: 5:1 (offset 75)
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 5:7 (offset 81)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:7 (offset 81)
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:11 (offset 85)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 5:11 (offset 85)
   |  |  |  |  |  name: null
   |  |  |  position: 5:1 (offset 75)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 5:1 (offset 75)
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\IssetNode
   |  |  |  vars: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 6:7 (offset 98)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 6:11 (offset 102)
   |  |  |  position: 6:1 (offset 92)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 6:1 (offset 92)
   |  |  unpack: false
   position: null
