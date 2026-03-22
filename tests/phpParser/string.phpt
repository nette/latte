<?php declare(strict_types=1);

// Constant string syntaxes

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	'',
	"",
	'Hi',
	"Hi",
	'!\'!\\!\a!',
	"!\"!\\!\$!\n!\r!\t!\f!\v!\e!\a",
	"!\xFF!\377!\0!",
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (7)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: ''
   |  |  |  position: 1:1+2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+2
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: ''
   |  |  |  position: 2:1+2
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+2
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Hi'
   |  |  |  position: 3:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+4
   |  3 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Hi'
   |  |  |  position: 4:1+4
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 4:1+4
   |  4 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: '!'!\!\a!'
   |  |  |  position: 5:1+12
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 5:1+12
   |  5 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: string
   |  |  |  |  '!"!\!$!\n
   |  |  |  |   !\r!\t    !\x0C!\x0B!\e!\a'
   |  |  |  position: 6:1+32
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 6:1+32
   |  6 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: '!\xFF!\xFF!\x00!'
   |  |  |  position: 7:1+16
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 7:1+16
   position: 1:1+85
