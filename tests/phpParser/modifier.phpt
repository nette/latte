<?php declare(strict_types=1);

// Modifier

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	|truncate: 10, (20|round)|trim
	XX;

$code = normalizeNl($test);
$tokens = (new TagLexer)->tokenize($code);
$parser = new Latte\Compiler\TagParser($tokens);
$node = $parser->parseModifier();
if (!$parser->isEnd()) {
	$parser->stream->throwUnexpectedException();
}

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\ModifierNode
   check: true
   filters: array (2)
   |  0 => Latte\Compiler\Nodes\Php\FilterNode
   |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  name: 'truncate'
   |  |  |  position: 1:2
   |  |  args: array (2)
   |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 10
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 1:12
   |  |  |  |  byRef: false
   |  |  |  |  unpack: false
   |  |  |  |  name: null
   |  |  |  |  position: 1:12
   |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 1:17
   |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  position: 1:20
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  nullsafe: false
   |  |  |  |  |  |  position: 1:19
   |  |  |  |  |  position: 1:17
   |  |  |  |  byRef: false
   |  |  |  |  unpack: false
   |  |  |  |  name: null
   |  |  |  |  position: 1:16
   |  |  nullsafe: false
   |  |  position: 1:1
   |  1 => Latte\Compiler\Nodes\Php\FilterNode
   |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  name: 'trim'
   |  |  |  position: 1:27
   |  |  args: array (0)
   |  |  nullsafe: false
   |  |  position: 1:26
   escape: false
   position: 1:1
