<?php declare(strict_types=1);

// Unicode escape sequence

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"\u{0}",
	"\u{114}",
	"\u{1F602}",
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expression\ArrayNode
   items: array (3)
   |  0 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: '\x00'
   |  |  |  position: 1:1+7
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 1:1+7
   |  1 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Ĕ'
   |  |  |  position: 2:1+9
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 2:1+9
   |  2 => Latte\Compiler\Nodes\Php\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: '😂'
   |  |  |  position: 3:1+11
   |  |  key: null
   |  |  byRef: false
   |  |  unpack: false
   |  |  position: 3:1+11
   position: 1:1+32
