<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php as Nodes;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\ContentType;
use function addcslashes, array_map, array_pop, end, implode, preg_replace, preg_replace_callback, strtolower, substr, trim, ucfirst;


/**
 * PHP printing helpers and context.
 */
final class PrintContext
{
	/** associativity */
	private const
		Left = -1,
		None = 0,
		Right = 1;

	public array $paramsExtraction = [];
	public array $blocks = [];

	private array $operatorPrecedence = [
		// [precedence, associativity]
		'new'        => [270, self::None], // also clone
		'**'         => [250, self::Right],
		'++x'        => [240, self::Right],
		'x++'        => [240, self::Left],
		'~'          => [240, self::Right], // also unary + -
		'(type)'     => [240, self::Right],
		'@'          => [240, self::Right],
		'!'          => [240, self::Right],
		'instanceof' => [230, self::None],
		'*'          => [210, self::Left],
		'/'          => [210, self::Left],
		'%'          => [210, self::Left],
		'+'          => [200, self::Left],
		'-'          => [200, self::Left],
		'<<'         => [190, self::Left],
		'>>'         => [190, self::Left],
		'.'          => [185, self::Left],
		'<'          => [180, self::None],
		'<='         => [180, self::None],
		'>'          => [180, self::None],
		'>='         => [180, self::None],
		'<=>'        => [180, self::None],
		'=='         => [170, self::None],
		'!='         => [170, self::None],
		'==='        => [170, self::None],
		'!=='        => [170, self::None],
		'&'          => [160, self::Left],
		'^'          => [150, self::Left],
		'|'          => [140, self::Left],
		'&&'         => [130, self::Left],
		'||'         => [120, self::Left],
		'??'         => [110, self::Right],
		'?:'         => [100, self::None],
		'='          => [90,  self::Right],
		'and'        => [50,  self::Left],
		'xor'        => [40,  self::Left],
		'or'         => [30,  self::Left],
	];

	private int $counter = 0;

	/** @var Escaper[] */
	private array $escaperStack = [];


	public function __construct(string $contentType = ContentType::Html)
	{
		$this->escaperStack[] = new Escaper($contentType);
	}


	/**
	 * Expands %node, %dump, %raw, %args, %line, %escape(), %modify(), %modifyContent() in code.
	 */
	public function format(string $mask, mixed ...$args): string
	{
		$pos = 0; // enumerate arguments except for %escape
		$mask = preg_replace_callback(
			'#%([a-z]{3,})#i',
			function ($m) use (&$pos) {
				return $m[1] === 'escape'
					? '%0.escape'
					: '%' . ($pos++) . '.' . $m[1];
			},
			$mask,
		);

		$mask = preg_replace_callback(
			'#% (\d+) \. (escape|modify(?:Content)?) ( \( ([^()]*+|(?-2))+ \) )#xi',
			function ($m) use ($args) {
				[, $pos, $fn, $var] = $m;
				$var = substr($var, 1, -1);
				/** @var Nodes\ModifierNode[] $args */
				return match ($fn) {
					'modify' => $args[$pos]->printSimple($this, $var),
					'modifyContent' => $args[$pos]->printContentAware($this, $var),
					'escape' => end($this->escaperStack)->escape($var),
				};
			},
			$mask,
		);

		return preg_replace_callback(
			'#([,+]?\s*)? % (\d+) \. ([a-z]{3,}) (\?)? (\s*\+\s*)? ()#xi',
			function ($m) use ($args) {
				[, $left, $pos, $format, $cond, $right] = $m;
				$arg = $args[$pos];

				$code = match ($format) {
					'dump' => PhpHelpers::dump($arg),
					'node' => match (true) {
						!$arg => '',
						$arg instanceof Nodes\ExpressionNode => $this->parenthesize($arg, $this->operatorPrecedence['='], self::Right),
						default => $arg->print($this),
					},
					'raw' => (string) $arg,
					'args' => $this->implode($arg instanceof Expression\ArrayNode ? $arg->toArguments() : $arg),
					'line' => $arg?->line ? "/* line $arg->line */" : '',
				};

				if ($cond && ($code === '[]' || $code === '')) {
					return $right ? $left : $right;
				}

				return $code === ''
					? trim($left) . $right
					: $left . $code . $right;
			},
			$mask,
		);
	}


	public function beginEscape(): Escaper
	{
		return $this->escaperStack[] = $this->getEscaper();
	}


	public function restoreEscape(): void
	{
		array_pop($this->escaperStack);
	}


	public function getEscaper(): Escaper
	{
		return clone end($this->escaperStack);
	}


	public function addBlock(Block $block): void
	{
		$block->escaping = $this->getEscaper()->export();
		$block->method = 'block' . ucfirst(trim(preg_replace('#\W+#', '_', $block->name->print($this)), '_'));
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
		$precedence = $this->getPrecedence($node);
		return $this->parenthesize($leftNode, $precedence, self::Left)
			. $operatorString
			. $this->parenthesize($rightNode, $precedence, self::Right);
	}


	/**
	 * Prints a prefix operation while taking precedence into account.
	 */
	public function prefixOp(Node $node, string $operatorString, Node $expr): string
	{
		return $operatorString . $this->parenthesize($expr, $this->getPrecedence($node), self::Right);
	}


	/**
	 * Prints a postfix operation while taking precedence into account.
	 */
	public function postfixOp(Node $node, Node $var, string $operatorString): string
	{
		return $this->parenthesize($var, $this->getPrecedence($node), self::Left) . $operatorString;
	}


	/**
	 * Prints an expression node with the least amount of parentheses necessary to preserve the meaning.
	 */
	private function parenthesize(Node $node, array $parent, int $childPosition): string
	{
		[$childPrecedence] = $this->getPrecedence($node);
		if ($childPrecedence) {
			[$parentPrecedence, $parentAssociativity] = $parent;
			if ($childPrecedence < $parentPrecedence
				|| ($parentPrecedence === $childPrecedence && $parentAssociativity !== $childPosition)
			) {
				return '(' . $node->print($this) . ')';
			}
		}

		return $node->print($this);
	}


	private function getPrecedence(Node $node): ?array
	{
		return $this->operatorPrecedence[match (true) {
			$node instanceof Expression\BinaryOpNode => $node->operator,
			$node instanceof Expression\PreOpNode => '++x',
			$node instanceof Expression\PostOpNode => 'x++',
			$node instanceof Expression\UnaryOpNode => '~',
			$node instanceof Expression\CastNode => '(type)',
			$node instanceof Expression\ErrorSuppressNode => '@',
			$node instanceof Expression\InstanceofNode => 'instanceof',
			$node instanceof Expression\NotNode => '!',
			$node instanceof Expression\TernaryNode => '?:',
			$node instanceof Expression\AssignNode, $node instanceof Expression\AssignOpNode => '=',
			default => null,
		}] ?? null;
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
		return $node instanceof Nodes\NameNode || $node instanceof Nodes\IdentifierNode
			? (string) $node
			: '{' . $node->print($this) . '}';
	}


	public function memberAsString(Node $node): string
	{
		return $node instanceof Nodes\NameNode || $node instanceof Nodes\IdentifierNode
			? $this->encodeString((string) $node)
			: $node->print($this);
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
			|| $expr instanceof Expression\FunctionCallableNode
			|| $expr instanceof Expression\MethodCallNode
			|| $expr instanceof Expression\MethodCallableNode
			|| $expr instanceof Expression\StaticMethodCallNode
			|| $expr instanceof Expression\StaticMethodCallableNode
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
			|| $expr instanceof Expression\StaticPropertyFetchNode
			|| $expr instanceof Expression\FunctionCallNode
			|| $expr instanceof Expression\FunctionCallableNode
			|| $expr instanceof Expression\MethodCallNode
			|| $expr instanceof Expression\MethodCallableNode
			|| $expr instanceof Expression\StaticMethodCallNode
			|| $expr instanceof Expression\StaticMethodCallableNode
			|| $expr instanceof Expression\ArrayNode
			|| $expr instanceof Scalar\StringNode
			|| $expr instanceof Scalar\BooleanNode
			|| $expr instanceof Scalar\NullNode
			|| $expr instanceof Expression\ConstantFetchNode
			|| $expr instanceof Expression\ClassConstantFetchNode
			? $expr->print($this)
			: '(' . $expr->print($this) . ')';
	}


	/**
	 * @param  Nodes\ArgumentNode[]  $args
	 */
	public function argumentsAsArray(array $args): string
	{
		$items = array_map(fn(Nodes\ArgumentNode $arg) => $arg->toArrayItem(), $args);
		return '[' . $this->implode($items) . ']';
	}


	/**
	 * Ensures that expression evaluates to string or throws exception.
	 */
	public function ensureString(Nodes\ExpressionNode $name, string $entity): string
	{
		return $name instanceof Scalar\StringNode
			? $name->print($this)
			: $this->format(
				'(LR\Helpers::stringOrNull($ʟ_tmp = %node) ?? throw new InvalidArgumentException(sprintf(%dump, get_debug_type($ʟ_tmp))))',
				$name,
				$entity . ' must be a string, %s given.',
			);
	}
}
