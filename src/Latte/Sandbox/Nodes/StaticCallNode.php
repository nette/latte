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
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\PrintContext;


class StaticCallNode extends CallLikeNode
{
	public Php\NameNode|ExpressionNode $class;
	public IdentifierNode|ExpressionNode $name;


	public function __construct(Php\Expression\StaticCallNode $source)
	{
		$this->class = $source->class;
		$this->name = $source->name;
		$this->args = $source->args;
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->call([' . $context->propertyAsValue($this->class) . ', ' . $context->propertyAsValue($this->name) . '])'
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
