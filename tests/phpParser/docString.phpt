<?php

// Nowdoc and heredoc strings

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* empty strings */
	<<<'EOS'
	EOS,
	<<<EOS
	EOS,

	/* constant encapsed strings */
	<<<'EOS'
	Test '" $a \n
	EOS,
	<<<EOS
	Test '" \$a \n
	EOS,

	/* encapsed strings */
	<<<EOS
	Test $a
	EOS,
	<<<EOS
	Test $a and $b->c test
	EOS,
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
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: ''
   |  |  |  position: 2:1 (offset 20)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1 (offset 20)
   |  1 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: ''
   |  |  |  position: 4:1 (offset 34)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1 (offset 34)
   |  2 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Test '" $a \n'
   |  |  |  position: 8:1 (offset 79)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1 (offset 79)
   |  3 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Test '" $a \n'
   |  |  |  position: 11:1 (offset 107)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1 (offset 107)
   |  4 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: 'Test '
   |  |  |  |  |  position: 17:1 (offset 165)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 17:6 (offset 170)
   |  |  |  position: 16:1 (offset 158)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1 (offset 158)
   |  5 => Latte\Compiler\Nodes\Php\Expression\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (5)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: 'Test '
   |  |  |  |  |  position: 20:1 (offset 185)
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 20:6 (offset 190)
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: ' and '
   |  |  |  |  |  position: 20:8 (offset 192)
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 20:13 (offset 197)
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 20:17 (offset 201)
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 20:13 (offset 197)
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: ' test'
   |  |  |  |  |  position: 20:18 (offset 202)
   |  |  |  position: 19:1 (offset 178)
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1 (offset 178)
   position: 2:1 (offset 20)
