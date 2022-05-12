<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class MethodCallNode extends ExpressionNode
{
	public function __construct(
		public ExpressionNode $object,
		public IdentifierNode|ExpressionNode $name,
		/** @var array<Php\ArgumentNode|Php\VariadicPlaceholderNode> */
		public array $args = [],
		public ?Position $position = null,
	) {
		(function (Php\ArgumentNode|Php\VariadicPlaceholderNode ...$args) {})(...$args);
	}


	public function isFirstClassCallable(): bool
	{
		return ($this->args[0] ?? null) instanceof Php\VariadicPlaceholderNode;
	}


	public function print(PrintContext $context): string
	{
		return $context->dereferenceExpr($this->object)
			. '->'
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
	}
}
