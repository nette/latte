<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php as Nodes;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Context;
use Latte\Policy;
use Latte\SecurityViolationException;
use Latte\Strict;


/**
 * PHP printing helpers and context.
 * The parts are based on great nikic/PHP-Parser project by Nikita Popov.
 */
final class PrintContext
{
	use Strict;

	public ?Policy $policy;
	public array $functions;
	public array $paramsExtraction = [];
	public array $blocks = [];

	private $exprPrecedenceMap = [
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

	private $binaryPrecedenceMap = [
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
	private string $contentType = Context::Html;
	private ?string $context = null;
	private ?string $subContext = null;


	public function format(string $mask, mixed ...$args): string
	{
		return PhpWriter::using($this)
			->write($mask, ...$args);
	}


	public function checkFilterIsAllowed(string $name): void
	{
		if ($this->policy && !$this->policy->isFilterAllowed($name)) {
			throw new SecurityViolationException("Filter |$name is not allowed.");
		}
	}


	public function generateId(): int
	{
		return $this->counter++;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		$this->context = null;
		return $this;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	public function setEscapingContext(?string $context, ?string $subContext = null): static
	{
		$this->context = $context;
		$this->subContext = $subContext;
		return $this;
	}


	public function getEscapingContext(): array
	{
		return [$this->contentType, $this->context, $this->subContext];
	}


	public function addBlock(Block $block, ?array $context = null): void
	{
		$block->context = implode('', $context ?? $this->getEscapingContext());
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


	public function objectProperty($node): string
	{
		if ($node instanceof ExpressionNode) {
			return '{' . $node->print($this) . '}';
		} else {
			return (string) $node;
		}
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


	public function escape(string $str): string
	{
		return match ($this->contentType) {
			Context::Html =>
				match ($this->context) {
					Context::HtmlText => 'LR\Filters::escapeHtmlText(' . $str . ')',
					Context::HtmlTag => 'LR\Filters::escapeHtmlAttrUnquoted(' . $str . ')',
					Context::HtmlAttribute,
					Context::HtmlAttributeUrl => 'LR\Filters::escapeHtmlAttr(' . $str . ')',
					Context::HtmlAttributeJavaScript => 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs(' . $str . '))',
					Context::HtmlAttributeCss => 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss(' . $str . '))',
					Context::HtmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
					Context::HtmlBogusTag => 'LR\Filters::escapeHtml(' . $str . ')',
					Context::HtmlJavaScript,
					Context::HtmlCss => 'LR\Filters::escape' . ucfirst($this->context) . '(' . $str . ')',
					default => throw new \LogicException("Unknown context $this->contentType, $this->context."),
				},
			Context::Xml =>
				match ($this->context) {
					Context::XmlText,
					Context::XmlAttribute,
					Context::XmlBogusTag => 'LR\Filters::escapeXml(' . $str . ')',
					Context::XmlComment => 'LR\Filters::escapeHtmlComment(' . $str . ')',
					Context::XmlTag => 'LR\Filters::escapeXmlAttrUnquoted(' . $str . ')',
					default => throw new \LogicException("Unknown context $this->contentType, $this->context."),
				},
			Context::JavaScript,
			Context::Css,
			Context::ICal => 'LR\Filters::escape' . ucfirst($this->contentType) . '(' . $str . ')',
			Context::Text => $str,
			default => throw new \LogicException("Unknown context $this->contentType."),
		};
	}
}
