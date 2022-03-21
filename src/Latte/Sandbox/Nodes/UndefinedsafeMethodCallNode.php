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


class UndefinedsafeMethodCallNode extends CallLikeNode
{
	public ExpressionNode $object;
	public IdentifierNode|ExpressionNode $name;


	public function __construct(Php\Expression\UndefinedsafeMethodCallNode $source)
	{
		$this->object = $source->object;
		$this->name = $source->name;
		$this->args = $source->args;
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->callMethod(' . $context->dereferenceExpr($this->object) . ' ?? null, ' . $context->propertyAsValue($this->name) . ')'
			. '?->__invoke'
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
