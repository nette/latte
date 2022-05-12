<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\Compiler\Nodes\ExpressionNode;
use Latte\Compiler\Nodes\Php as Nodes;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\ContentType;
use Latte\Policy;
use Latte\SecurityViolationException;


/**
 * PHP printing helpers and context.
 * The parts are based on great nikic/PHP-Parser project by Nikita Popov.
 */
final class PrintContext
{
	use Latte\Strict;

	public ?Policy $policy;
	public array $functions;
	public array $paramsExtraction = [];
	public array $blocks = [];

	private array $exprPrecedenceMap = [
		// [precedence, associativity] (-1 is %left, 0 is %nonassoc and 1 is %right)
		Expression\PreOpNode::class              => [10,  1],
		Expression\PostOpNode::class             => [10, -1],
		Expression\UnaryOpNode::class            => [10,  1],
		Expression\CastNode::class               => [10,  1],
		Expression\ErrorSuppressNode::class      => [10,  1],
		Expression\InstanceofNode::class         => [20,  0],
		Expression\NotNode::class                => [30,  1],
		Expression\TernaryNode::class            => [150,  0],
		// parser uses %left for assignments, but they really behave as %right
		Expression\AssignNode::class             => [160,  1],
		Expression\AssignOpNode::class           => [160,  1],
	];

	private array $binaryPrecedenceMap = [
		// [precedence, associativity] (-1 is %left, 0 is %nonassoc and 1 is %right)
		'**'  => [0, 1],
		'*'   => [40, -1],
		'/'   => [40, -1],
		'%'   => [40, -1],
		'+'   => [50, -1],
		'-'   => [50, -1],
		'.'   => [50, -1],
		'<<'  => [60, -1],
		'>>'  => [60, -1],
		'<'   => [70, 0],
		'<='  => [70, 0],
		'>'   => [70, 0],
		'>='  => [70, 0],
		'=='  => [80, 0],
		'!='  => [80, 0],
		'===' => [80, 0],
		'!==' => [80, 0],
		'<=>' => [80, 0],
		'&'   => [90, -1],
		'^'   => [100, -1],
		'|'   => [110, -1],
		'&&'  => [120, -1],
		'||'  => [130, -1],
		'??'  => [140, 1],
		'and' => [170, -1],
		'xor' => [180, -1],
		'or'  => [190, -1],
	];
	private int $counter = 0;
	private Escaper $escaper;

	/** @var Escaper[] */
	private array $escaperStack = [];


	public function __construct(string $contentType = ContentType::Html)
	{
		$this->escaper = new Escaper($contentType);
	}


	/**
	 * Expands %node, %dump, %raw, %args, %line, %escape(), %modify(), %modifyContent() in code.
	 */
	public function format(string $mask, mixed ...$args): string
	{
		$writer = PhpWriter::using($this);
		$pos = 0;
		$mask = preg_replace_callback(
			'#%([a-z])#',
			function ($m) use (&$pos) { return '%' . ($pos++) . '.' . $m[1]; },
			$mask,
		);

		$mask = preg_replace_callback(
			'#%(\d+)\.modify(Content)?(\(([^()]*+|(?-2))+\))#',
			function ($m) use ($writer, $args) {
				[, $pos, $content, $var] = $m;
				return $writer->formatModifiers($args[$pos], substr($var, 1, -1), (bool) $content);
			},
			$mask,
		);

		return preg_replace_callback(
			'#([,+]?\s*)?%(\d+)\.(node|word|dump|raw|array|args|line)(\?)?(\s*\+\s*)?()#',
			function ($m) use ($writer, $args) {
				[, $l, $pos, $format, $cond, $r] = $m;
				$arg = $args[$pos];

				switch ($format) {
					case 'node':
						$code = $arg ? $arg->print($this) : '';
						break;
					case 'word':
						if ($arg instanceof ExpressionNode) {
							$arg = $arg->text;
						}
						$code = $writer->formatWord($arg); break;
					case 'args':
						if ($arg instanceof ExpressionNode) {
							$arg = new MacroTokens($arg->text);
						}
						$code = $writer->formatArgs($arg); break;
					case 'array':
						if ($arg instanceof ExpressionNode) {
							$arg = new MacroTokens($arg->text);
						}
						$code = $writer->formatArray($arg);
						$code = $cond && $code === '[]' ? '' : $code; break;
					case 'dump':
						$code = PhpHelpers::dump($arg); break;
					case 'raw':
						$code = (string) $arg;
						break;
					case 'line':
						$l = trim($l);
						$line = (int) $arg->line;
						$code = $line ? " /* line $line */" : '';
						break;
				}

				if ($cond && $code === '') {
					return $r ? $l : $r;
				} else {
					return $l . $code . $r;
				}
			},
			$mask,
		);
	}


	public function beginEscape(): Escaper
	{
		$this->escaperStack[] = $this->escaper;
		return $this->escaper = clone $this->escaper;
	}


	public function restoreEscape(): void
	{
		$this->escaper = array_pop($this->escaperStack);
	}


	public function getEscaper(): Escaper
	{
		return clone $this->escaper;
	}


	public function addBlock(Block $block, ?Escaper $escaper = null): void
	{
		$block->escaping = ($escaper ?? $this->getEscaper())->export();
		$block->method = 'block' . ucfirst(trim(preg_replace('#\W+#', '_', $block->name), '_'));
		$lower = strtolower($block->method);
		$used = $this->blocks + ['block' => 1];
		$counter = null;
		while (isset($used[$lower . $counter])) {
			$counter++;
		}

		$block->method .= $counter;
		$this->blocks[$lower . $counter] = $block;
	}


	public function generateId(): int
	{
		return $this->counter++;
	}


	public function checkFilterIsAllowed(string $name): void
	{
		if ($this->policy && !$this->policy->isFilterAllowed($name)) {
			throw new SecurityViolationException("Filter |$name is not allowed.");
		}
	}


	// PHP helpers


	public function encodeString(string $str, string $quote = "'"): string
	{
		return $quote === "'"
			? "'" . addcslashes($str, "'\\") . "'"
			: '"' . addcslashes($str, "\n\r\t\f\v$\"\\") . '"';
	}


	/**
	 * Prints an infix operation while taking precedence into account.
	 */
	public function infixOp(Node $node, Node $leftNode, string $operatorString, Node $rightNode): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $this->prec($leftNode, $precedence, $associativity, -1)
			. $operatorString
			. $this->prec($rightNode, $precedence, $associativity, 1);
	}


	/**
	 * Prints a prefix operation while taking precedence into account.
	 */
	public function prefixOp(Node $node, string $operatorString, Node $expr): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $operatorString . $this->prec($expr, $precedence, $associativity, 1);
	}


	/**
	 * Prints a postfix operation while taking precedence into account.
	 */
	public function postfixOp(Node $node, Node $var, string $operatorString): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $this->prec($var, $precedence, $associativity, -1) . $operatorString;
	}


	/**
	 * Prints an expression node with the least amount of parentheses necessary to preserve the meaning.
	 */
	private function prec(Node $node, int $parentPrecedence, int $parentAssociativity, int $childPosition): string
	{
		$precedence = $this->getPrecedence($node);
		if ($precedence) {
			$childPrecedence = $precedence[0];
			if ($childPrecedence > $parentPrecedence
				|| ($parentPrecedence === $childPrecedence && $parentAssociativity !== $childPosition)
			) {
				return '(' . $node->print($this) . ')';
			}
		}

		return $node->print($this);
	}


	private function getPrecedence(Node $node): ?array
	{
		return $node instanceof Expression\BinaryOpNode
			? $this->binaryPrecedenceMap[$node->operator]
			: $this->exprPrecedenceMap[$node::class] ?? null;
	}


	/**
	 * Prints an array of nodes and implodes the printed values with $glue
	 */
	public function implode(array $nodes, string $glue = ', '): string
	{
		$pNodes = [];
		foreach ($nodes as $node) {
			if ($node === null) {
				$pNodes[] = '';
			} else {
				$pNodes[] = $node->print($this);
			}
		}

		return implode($glue, $pNodes);
	}


	public function objectProperty(Node $node): string
	{
		return $node instanceof Nodes\ExpressionNode
			? '{' . $node->print($this) . '}'
			: (string) $node;
	}


	/**
	 * Wraps the LHS of a call in parentheses if needed.
	 */
	public function callExpr(Node $expr): string
	{
		return $expr instanceof Nodes\NameNode
			|| $expr instanceof Expression\VariableNode
			|| $expr instanceof Expression\ArrayAccessNode
			|| $expr instanceof Expression\FunctionCallNode
			|| $expr instanceof Expression\MethodCallNode
			|| $expr instanceof Expression\NullsafeMethodCallNode
			|| $expr instanceof Expression\StaticCallNode
			|| $expr instanceof Expression\ArrayNode
			? $expr->print($this)
			: '(' . $expr->print($this) . ')';
	}


	/**
	 * Wraps the LHS of a dereferencing operation in parentheses if needed.
	 */
	public function dereferenceExpr(Node $expr): string
	{
		return $expr instanceof Expression\VariableNode
			|| $expr instanceof Nodes\NameNode
			|| $expr instanceof Expression\ArrayAccessNode
			|| $expr instanceof Expression\PropertyFetchNode
			|| $expr instanceof Expression\NullsafePropertyFetchNode
			|| $expr instanceof Expression\StaticPropertyFetchNode
			|| $expr instanceof Expression\FunctionCallNode
			|| $expr instanceof Expression\MethodCallNode
			|| $expr instanceof Expression\NullsafeMethodCallNode
			|| $expr instanceof Expression\StaticCallNode
			|| $expr instanceof Expression\ArrayNode
			|| $expr instanceof Scalar\StringNode
			|| $expr instanceof Scalar\BooleanNode
			|| $expr instanceof Scalar\NullNode
			|| $expr instanceof Expression\ConstantFetchNode
			|| $expr instanceof Expression\ClassConstantFetchNode
			? $expr->print($this)
			: '(' . $expr->print($this) . ')';
	}
}
