<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Helpers;


/**
 * Method call with nullsafe support, partial application, or first-class callable.
 */
class MethodCallNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $object,
		public IdentifierNode|ExpressionNode $name,
		/** @var array<Php\ArgumentNode|Php\VariadicPlaceholderNode> */
		public array $args = [],
		public bool $nullsafe = false,
		public ?Position $position = null,
	) {
		(function (Php\ArgumentNode|Php\VariadicPlaceholderNode ...$args) {})(...$args);
	}


	public function isPartialFunction(): bool
	{
		return ($this->args[0] ?? null) instanceof Php\VariadicPlaceholderNode;
	}


	public function print(PrintContext $context): string
	{
		if ($this->nullsafe && $this->isPartialFunction()) {
			throw new CompileException('Cannot combine nullsafe operator with Closure creation', $this->position);
		}
		return $context->dereferenceExpr($this->object)
			. ($this->nullsafe ? '?->' : '->')
			. $context->objectProperty($this->name)
			. '(' . $context->implode($this->args) . ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->object;
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
		Helpers::removeNulls($this->args);
	}
}
