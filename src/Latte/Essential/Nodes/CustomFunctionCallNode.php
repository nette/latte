<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;
use Latte\Helpers;


/**
 * Custom function call node.
 */
class CustomFunctionCallNode extends ExpressionNode
{
	public function __construct(
		public Php\NameNode $name,
		/** @var array<Php\ArgumentNode> */
		public array $args = [],
		public ?Position $position = null,
	) {
		(function (Php\ArgumentNode ...$args) {})(...$args);
	}


	public function print(PrintContext $context): string
	{
		return '($this->global->fn->' . $this->name . ')($this, ' . $context->implode($this->args) . ')';
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
