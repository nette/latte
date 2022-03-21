<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression\CallLikeNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\PrintContext;


class FunctionCallNode extends CallLikeNode
{
	public Php\NameNode|ExpressionNode $name;


	public function __construct(Php\Expression\FunctionCallNode $source)
	{
		$this->name = $source->name;
		$this->args = $source->args;
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->call(' . $context->propertyAsValue($this->name) . ')'
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
