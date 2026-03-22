<?php declare(strict_types=1);

// Undefined operator

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a??->b,
	$a??->b($c),
	new $a??->b,
	"{$a??->b}",
	"$a??->b",
	XX;

$node = @parseCode($test); // deprecated

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (5)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 1:1+2
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  position: 1:1+7
   |  |  |  |  position: 1:1+7
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 1:7+1
   |  |  |  nullsafe: true
   |  |  |  position: 1:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+7
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 2:1+2
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  position: 2:1+11
   |  |  |  |  position: 2:1+11
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:7+1
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 2:9+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:9+2
   |  |  |  nullsafe: true
   |  |  |  position: 2:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+11
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:5+2
   |  |  |  |  |  operator: '??'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  position: 3:5+7
   |  |  |  |  |  position: 3:5+7
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 3:11+1
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 3:5+7
   |  |  |  args: array (0)
   |  |  |  position: 3:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+11
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 4:3+2
   |  |  |  |  |  |  operator: '??'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  |  position: 4:3+7
   |  |  |  |  |  |  position: 4:3+7
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 4:9+1
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 4:3+7
   |  |  |  position: 4:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+11
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 5:2+2
   |  |  |  |  |  |  operator: '??'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  |  position: 5:2+7
   |  |  |  |  |  |  position: 5:2+7
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:8+1
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 5:2+7
   |  |  |  position: 5:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+9
   position: 1:1+58
