<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\{Position, PrintContext};


/**
 * Wrapper for custom function calls in Latte expressions.
 */
class CustomFunctionCallNode extends ExpressionNode
{
	public function __construct(
		public Php\NameNode $name,
		/** @var array<Php\ArgumentNode|Php\VariadicPlaceholderNode|Php\ArgumentPlaceholderNode> */
		public array $args = [],
		public ?Position $position = null,
		public ?Position $end = null,
	) {
		(function (Php\ArgumentNode|Php\VariadicPlaceholderNode|Php\ArgumentPlaceholderNode ...$args) {})(...$args);
	}


	public function print(PrintContext $context): string
	{
		return '($this->global->fn->' . $this->name . ')($this, ' . $context->implode($this->args) . ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		foreach (self::walk(
			$this->args,
			fn(Php\ArgumentNode|Php\VariadicPlaceholderNode|Php\ArgumentPlaceholderNode ...$args) => null,
		) as &$item) {
			yield $item;
		}
	}
}
