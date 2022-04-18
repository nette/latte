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


class MethodCallNode extends CallLikeNode
{
	public function __construct(
		public ExpressionNode $object,
		public IdentifierNode|ExpressionNode $name,
		/** @var array<Php\ArgumentNode|Php\VariadicPlaceholderNode> */
		public array $args = [],
		public ?Position $position = null,
	) {
	}


	public static function from(
		ExpressionNode $var,
		string|IdentifierNode|ExpressionNode $name,
		array $args = [],
	): static {
		return new static(
			$var,
			is_string($name) ? new IdentifierNode($name) : $name,
			self::argumentsFromValues($args),
		);
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
