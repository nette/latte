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

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (5)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 1:1
   |  |  |  |  |  end: 1:3
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  position: 1:1
   |  |  |  |  |  end: 1:8
   |  |  |  |  position: 1:1
   |  |  |  |  end: 1:8
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 1:7
   |  |  |  |  end: 1:8
   |  |  |  nullsafe: true
   |  |  |  position: 1:1
   |  |  |  end: 1:8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1
   |  |  end: 1:8
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 2:1
   |  |  |  |  |  end: 2:3
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  position: 2:1
   |  |  |  |  |  end: 2:12
   |  |  |  |  position: 2:1
   |  |  |  |  end: 2:12
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:7
   |  |  |  |  end: 2:8
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 2:9
   |  |  |  |  |  |  end: 2:11
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:9
   |  |  |  |  |  end: 2:11
   |  |  |  nullsafe: true
   |  |  |  position: 2:1
   |  |  |  end: 2:12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1
   |  |  end: 2:12
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 3:5
   |  |  |  |  |  |  end: 3:7
   |  |  |  |  |  operator: '??'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  position: 3:5
   |  |  |  |  |  |  end: 3:12
   |  |  |  |  |  position: 3:5
   |  |  |  |  |  end: 3:12
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 3:11
   |  |  |  |  |  end: 3:12
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 3:5
   |  |  |  |  end: 3:12
   |  |  |  args: array (0)
   |  |  |  position: 3:1
   |  |  |  end: 3:12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1
   |  |  end: 3:12
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 4:3
   |  |  |  |  |  |  |  end: 4:5
   |  |  |  |  |  |  operator: '??'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  |  position: 4:3
   |  |  |  |  |  |  |  end: 4:10
   |  |  |  |  |  |  position: 4:3
   |  |  |  |  |  |  end: 4:10
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 4:9
   |  |  |  |  |  |  end: 4:10
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 4:3
   |  |  |  |  |  end: 4:10
   |  |  |  position: 4:1
   |  |  |  end: 4:12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1
   |  |  end: 4:12
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  position: 5:2
   |  |  |  |  |  |  |  end: 5:4
   |  |  |  |  |  |  operator: '??'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\NullNode
   |  |  |  |  |  |  |  position: 5:2
   |  |  |  |  |  |  |  end: 5:9
   |  |  |  |  |  |  position: 5:2
   |  |  |  |  |  |  end: 5:9
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:8
   |  |  |  |  |  |  end: 5:9
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 5:2
   |  |  |  |  |  end: 5:9
   |  |  |  position: 5:1
   |  |  |  end: 5:10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1
   |  |  end: 5:10
   position: 1:1
   end: 5:11
