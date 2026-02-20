<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php as Nodes;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\OperatorNode;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\ContentType;
use function addcslashes, array_map, array_pop, end, implode, preg_replace, preg_replace_callback, strtolower, substr, trim, ucfirst;


/**
 * Context for PHP code generation with escaping management.
 */
final class PrintContext
{
	/** @var Nodes\ParameterNode[] */
	public array $paramsExtraction = [];

	/** @var array<string, Block> */
	public array $blocks = [];
	private int $counter = 0;

	/** @var Escaper[] */
	private array $escaperStack = [];


	public function __construct(
		string $contentType = ContentType::Html,
		public bool $migrationWarnings = false,
	) {
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
					'escape' => $this->getEscaper()->escape($var),
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
						$arg instanceof OperatorNode && $arg->getOperatorPrecedence() < Expression\AssignNode::Precedence => '(' . $arg->print($this) . ')',
						default => $arg->print($this),
					},
					'raw' => (string) $arg,
					'args' => $this->implode($arg instanceof Expression\ArrayNode ? $arg->toArguments() : $arg),
					'line' => $arg?->line ? "/* pos $arg->line" . ($arg->column ? ":$arg->column" : '') . ' */' : '',
				};

				if ($cond && ($code === '[]' || $code === '' || $code === 'null')) {
					return $right ? $left : $right;
				}

				return $code === ''
					? trim($left) . $right
					: $left . $code . $right;
			},
			$mask,
		);
	}


	/**
	 * Pushes current escaper onto stack.
	 */
	public function beginEscape(): Escaper
	{
		return $this->escaperStack[] = $this->getEscaper();
	}


	/**
	 * Restores previous escaper from stack.
	 */
	public function restoreEscape(): void
	{
		array_pop($this->escaperStack);
	}


	public function getEscaper(): Escaper
	{
		$escaper = end($this->escaperStack);
		assert($escaper instanceof Escaper);
		return clone $escaper;
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


	/**
	 * Generates unique ID for temporary variables.
	 */
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


	#[\Deprecated]
	public function infixOp(Node $node, Node $leftNode, string $operatorString, Node $rightNode): string
	{
		return $this->parenthesize($node, $leftNode, OperatorNode::AssocLeft)
			. $operatorString
			. $this->parenthesize($node, $rightNode, OperatorNode::AssocRight);
	}


	#[\Deprecated]
	public function prefixOp(Node $node, string $operatorString, Node $expr): string
	{
		return $operatorString . $this->parenthesize($node, $expr, OperatorNode::AssocRight);
	}


	#[\Deprecated]
	public function postfixOp(Node $node, Node $var, string $operatorString): string
	{
		return $this->parenthesize($node, $var, OperatorNode::AssocLeft) . $operatorString;
	}


	/**
	 * Prints an expression node with the least amount of parentheses necessary to preserve the meaning.
	 */
	public function parenthesize(OperatorNode $parentNode, Node $childNode, int $childPosition): string
	{
		[$parentPrec, $parentAssoc] = $parentNode->getOperatorPrecedence();
		[$childPrec] = $childNode instanceof OperatorNode ? $childNode->getOperatorPrecedence() : [null];
		return $childPrec && ($childPrec < $parentPrec || ($parentPrec === $childPrec && $parentAssoc !== $childPosition))
			? '(' . $childNode->print($this) . ')'
			: $childNode->print($this);
	}


	/**
	 * Prints an array of nodes and implodes the printed values with $glue
	 * @param  (?Node)[]  $nodes
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
			|| $expr instanceof Expression\MethodCallNode
			|| $expr instanceof Expression\StaticMethodCallNode
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
			|| $expr instanceof Expression\MethodCallNode
			|| $expr instanceof Expression\StaticMethodCallNode
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
