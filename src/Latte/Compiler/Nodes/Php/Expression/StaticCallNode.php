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
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class StaticCallNode extends CallLikeNode
{
	public function __construct(
		public Php\NameNode|ExpressionNode $class,
		public IdentifierNode|ExpressionNode $name,
		/** @var array<Php\ArgumentNode|Php\VariadicPlaceholderNode> */
		public array $args = [],
		public ?Position $position = null,
	) {
	}


	public static function from(
		string|NameNode|ExpressionNode $class,
		string|IdentifierNode|ExpressionNode $name,
		array $args = [],
	): static {
		return new static(
			is_string($class) ? NameNode::fromString($class) : $class,
			is_string($name) ? new IdentifierNode($name) : $name,
			self::argumentsFromValues($args),
		);
	}


	public function print(PrintContext $context): string
	{
		$name = match (true) {
			$this->name instanceof VariableNode => $this->name->print($context),
			$this->name instanceof ExpressionNode => '{' . $this->name->print($context) . '}',
			default => $this->name,
		};
		return $context->dereferenceExpr($this->class)
			. '::'
			. $name
			. '(' . $context->implode($this->args) . ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->class;
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
