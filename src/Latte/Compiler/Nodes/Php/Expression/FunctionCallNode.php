<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expression;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;


class FunctionCallNode extends ExpressionNode
{
	public function __construct(
		public NameNode|ExpressionNode $name,
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
		if (PHP_VERSION_ID < 80100 && $this->isFirstClassCallable()) {
			return $context->memberAsString($this->name);
		}
		return $context->callExpr($this->name)
			. '(' . $context->implode($this->args) . ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
