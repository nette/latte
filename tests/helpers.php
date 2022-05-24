<?php

declare(strict_types=1);

use Latte\Compiler\Node;
use Latte\Compiler\Nodes;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Compiler\TagLexer;
use Latte\Compiler\Token;
use Tracy\Dumper;


function getTempDir(): string
{
	$dir = __DIR__ . '/tmp/' . getmypid();

	if (empty($GLOBALS['\\lock'])) {
		// garbage collector
		$GLOBALS['\\lock'] = $lock = fopen(__DIR__ . '/lock', 'w');
		if (rand(0, 100)) {
			flock($lock, LOCK_SH);
			@mkdir(dirname($dir));
		} elseif (flock($lock, LOCK_EX)) {
			Tester\Helpers::purge(dirname($dir));
		}

		@mkdir($dir);
	}

	return $dir;
}


function test(string $title, Closure $function): void
{
	$function();
}


function normalizeNl(string $s): string
{
	return str_replace("\r\n", "\n", $s);
}


function parseCode(string $code): Nodes\Php\Expression\ArrayNode
{
	$code = normalizeNl($code);
	$tokens = (new TagLexer)->tokenize($code);
	$parser = new Latte\Compiler\TagParser($tokens);
	$node = $parser->parseArguments();
	if (!$parser->isEnd()) {
		$parser->stream->throwUnexpectedException();
	}
	return $node;
}


function exportNode(Node $node): string
{
	$exporters = [
		Position::class => function (Position $pos, Tracy\Dumper\Value $value) {
			$value->value = $pos->line . ':' . $pos->column . ' (offset ' . $pos->offset . ')';
		},
	];
	$dump = Dumper::toText($node, [Dumper::HASH => false, Dumper::DEPTH => 20, Dumper::OBJECT_EXPORTERS => $exporters]);
	return trim($dump) . "\n";
}


function printNode(Nodes\Php\Expression\ArrayNode $node): string
{
	$context = new PrintContext;
	$code = $context->implode($node->items, ",\n");
	return $code . "\n";
}


function exportTokens(array $tokens): string
{
	static $table;
	if (!$table) {
		$table = @array_flip((new ReflectionClass(Token::class))->getConstants());
	}
	$res = '';
	foreach ($tokens as $token) {
		$res .= str_pad('#' . $token->position->line . ':' . $token->position->column, 6) . ' ';
		if (isset($table[$token->type])) {
			$res .= str_pad($table[$token->type], 15) . ' ';
		}
		$res .= "'" . addcslashes(normalizeNl($token->text), "\n\t\f\v\"\\") . "'\n";
	}

	return $res;
}


function loadContent(string $file, int $offset): string
{
	$s = file_get_contents($file);
	$s = substr($s, $offset);
	$s = normalizeNl(ltrim($s));
	return $s;
}


function exportAST(Node $node)
{
	$prop = match (true) {
		$node instanceof Nodes\TextNode => 'content: ' . var_export($node->content, true),
		$node instanceof Nodes\Html\ElementNode,
			$node instanceof Nodes\Php\IdentifierNode => 'name: ' . $node->name,
		$node instanceof Nodes\Php\NameNode => 'parts: ' . PhpHelpers::dump($node->parts),
		$node instanceof Nodes\Php\SuperiorTypeNode => PhpHelpers::dump($node->type),
		$node instanceof Nodes\Php\Scalar\FloatNode,
			$node instanceof Nodes\Php\Scalar\EncapsedStringPartNode,
			$node instanceof Nodes\Php\Scalar\IntegerNode,
			$node instanceof Nodes\Php\Scalar\StringNode => 'value: ' . $node->value,
		$node instanceof Nodes\Php\Expression\AssignOpNode,
			$node instanceof Nodes\Php\Expression\BinaryOpNode => 'operator: ' . $node->operator,
		$node instanceof Nodes\Php\Expression\CastNode => 'type: ' . $node->type,
		$node instanceof Nodes\Php\Expression\VariableNode && is_string($node->name) => 'name: ' . $node->name,
		default => '',
	};
	$res = $prop ? $prop . "\n" : '';
	foreach ($node as $sub) {
		$res .= rtrim(exportAST($sub), "\n") . "\n";
	}

	return substr($node::class, strrpos($node::class, '\\') + 1, -4)
		. ':'
		. ($res ? "\n" . preg_replace('#^(?=.)#m', "\t", $res) : '')
		. "\n";
}


function exportTraversing(string $template, ?Latte\Engine $latte = null): string
{
	$latte ??= new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$node = $latte->parse($template);
	return exportAST($node);
}
