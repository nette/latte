<?php declare(strict_types=1);

// Nowdoc and heredoc strings

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
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: ''
   |  |  |  position: 2:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+12
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: ''
   |  |  |  position: 4:1+10
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+10
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Test '" $a \n'
   |  |  |  position: 8:1+26
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 8:1+26
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Test '" $a \n'
   |  |  |  position: 11:1+25
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 11:1+25
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: 'Test '
   |  |  |  |  |  position: 17:1+5
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 17:6+2
   |  |  |  position: 16:1+18
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 16:1+18
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\InterpolatedStringNode
   |  |  |  parts: array (5)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: 'Test '
   |  |  |  |  |  position: 20:1+5
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  position: 20:6+2
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' and '
   |  |  |  |  |  position: 20:8+5
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expression\PropertyFetchNode
   |  |  |  |  |  object: Latte\Compiler\Nodes\Php\Expression\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  position: 20:13+2
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  position: 20:17+1
   |  |  |  |  |  nullsafe: false
   |  |  |  |  |  position: 20:13+5
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\InterpolatedStringPartNode
   |  |  |  |  |  value: ' test'
   |  |  |  |  |  position: 20:18+6
   |  |  |  position: 19:1+33
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 19:1+33
   position: 2:1+192
