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
use Latte\Helpers;


class FunctionCallNode extends ExpressionNode
{
	public function __construct(
		public NameNode|ExpressionNode $name,
		/** @var array<Php\ArgumentNode|Php\VariadicPlaceholderNode|Php\ArgumentPlaceholderNode> */
		public array $args = [],
		public ?Position $position = null,
	) {
		(function (Php\ArgumentNode|Php\VariadicPlaceholderNode|Php\ArgumentPlaceholderNode ...$args) {})(...$args);
	}


	public function isPartialFunction(): bool
	{
		return (bool) array_filter($this->args, fn($arg) => !$arg instanceof Php\ArgumentNode);
	}


	public function print(PrintContext $context): string
	{
		return $context->callPartial($context->callExpr($this->name), $this->args);
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
		Helpers::removeNulls($this->args);
	}
}
