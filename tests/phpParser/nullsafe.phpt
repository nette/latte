<?php declare(strict_types=1);

// Nullsafe operator

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a?->b,
	$a?->b($c),
	new $a?->b,
	"{$a?->b}",
	"$a?->b",
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
   |  |  value: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 1:6+1
   |  |  |  nullsafe: true
   |  |  |  position: 1:1+6
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+6
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\MethodCallNode
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:1+2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:6+1
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 2:8+2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  name: null
   |  |  |  |  |  position: 2:8+2
   |  |  |  nullsafe: true
   |  |  |  position: 2:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+10
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 3:5+2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 3:10+1
   |  |  |  |  nullsafe: true
   |  |  |  |  position: 3:5+6
   |  |  |  args: array (0)
   |  |  |  position: 3:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+10
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 4:3+2
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 4:8+1
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 4:3+6
   |  |  |  position: 4:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+10
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 5:2+2
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:7+1
   |  |  |  |  |  nullsafe: true
   |  |  |  |  |  position: 5:2+6
   |  |  |  position: 5:1+8
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+8
   position: 1:1+53
