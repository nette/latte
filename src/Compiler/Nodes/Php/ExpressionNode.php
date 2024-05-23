<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;


abstract class ExpressionNode extends Node
{
	public function isWritable(): bool
	{
		return $this instanceof Expression\ArrayAccessNode
			|| ($this instanceof Expression\PropertyFetchNode && !$this->nullsafe)
			|| $this instanceof Expression\StaticPropertyFetchNode
			|| $this instanceof Expression\VariableNode;
	}


	public function isVariable(): bool
	{
		return $this instanceof Expression\ArrayAccessNode
			|| $this instanceof Expression\PropertyFetchNode
			|| $this instanceof Expression\StaticPropertyFetchNode
			|| $this instanceof Expression\VariableNode;
	}


	public function isCall(): bool
	{
		return $this instanceof Expression\FunctionCallNode
			|| $this instanceof Expression\MethodCallNode
			|| $this instanceof Expression\StaticMethodCallNode;
	}
}
