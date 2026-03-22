<?php declare(strict_types=1);

// Modifier

use Latte\Compiler\TagLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	?|truncate: 10, (20?|round)?|trim
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

__halt_compiler();Latte\Compiler\Nodes\Php\ModifierNode
   check: true
   filters: array (2)
   |  0 => Latte\Compiler\Nodes\Php\FilterNode
   |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  name: 'truncate'
   |  |  |  position: 1:3+8
   |  |  args: array (2)
   |  |  |  0 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  value: 10
   |  |  |  |  |  kind: 10
   |  |  |  |  |  position: 1:13+2
   |  |  |  |  byRef: false
   |  |  |  |  unpack: false
   |  |  |  |  name: null
   |  |  |  |  position: 1:13+2
   |  |  |  1 => Latte\Compiler\Nodes\Php\ArgumentNode
   |  |  |  |  value: Latte\Compiler\Nodes\Php\Expression\FilterCallNode
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\IntegerNode
   |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  position: 1:18+2
   |  |  |  |  |  filter: Latte\Compiler\Nodes\Php\FilterNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  position: 1:22+5
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  nullsafe: true
   |  |  |  |  |  |  position: 1:20+7
   |  |  |  |  |  position: 1:18+9
   |  |  |  |  byRef: false
   |  |  |  |  unpack: false
   |  |  |  |  name: null
   |  |  |  |  position: 1:17+11
   |  |  nullsafe: true
   |  |  position: 1:1+27
   |  1 => Latte\Compiler\Nodes\Php\FilterNode
   |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  name: 'trim'
   |  |  |  position: 1:30+4
   |  |  args: array (0)
   |  |  nullsafe: true
   |  |  position: 1:28+6
   escape: false
   position: 1:1+33
