<?php

// Nullsafe operator

declare(strict_types=1);

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
   |  0 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NullsafePropertyFetchNode
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 1:6 (offset 5)
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 1:1 (offset 0)
   |  |  |  position: 1:1 (offset 0)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 1:1 (offset 0)
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NullsafeMethodCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'b'
   |  |  |  |  position: 2:6 (offset 13)
   |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  position: 2:1 (offset 8)
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 2:8 (offset 15)
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  position: 2:8 (offset 15)
   |  |  |  |  |  name: null
   |  |  |  position: 2:1 (offset 8)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 2:1 (offset 8)
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expression\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expression\NullsafePropertyFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  position: 3:10 (offset 29)
   |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 3:5 (offset 24)
   |  |  |  |  position: 3:5 (offset 24)
   |  |  |  args: array (0)
   |  |  |  position: 3:1 (offset 20)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 3:1 (offset 20)
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\NullsafePropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 4:8 (offset 39)
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 4:3 (offset 34)
   |  |  |  |  |  position: 4:3 (offset 34)
   |  |  |  position: 4:1 (offset 32)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 4:1 (offset 32)
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expression\NullsafePropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 5:7 (offset 50)
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  position: 5:2 (offset 45)
   |  |  |  |  |  position: 5:2 (offset 45)
   |  |  |  position: 5:1 (offset 44)
   |  |  key: null
   |  |  byRef: false
   |  |  position: 5:1 (offset 44)
   |  |  unpack: false
   position: null
