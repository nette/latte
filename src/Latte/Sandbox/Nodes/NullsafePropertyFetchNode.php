<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\PrintContext;



class NullsafePropertyFetchNode extends ExpressionNode
{
	public ExpressionNode $object;
	public IdentifierNode|ExpressionNode $name;


	public function __construct(Php\Expression\NullsafePropertyFetchNode $source)
	{
		$this->object = $source->object;
		$this->name = $source->name;
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->prop(' . $this->object->print($context) . ', ' . $context->propertyAsValue($this->name) . ')'
			. '?->'
			. $context->objectProperty($this->name);
	}


	public function &getIterator(): \Generator
	{
		yield $this->object;
		yield $this->name;
	}
}
