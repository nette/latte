<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\PrintContext;


/**
 * Static method call routed through sandbox security policy.
 */
class StaticMethodCallNode extends Expression\StaticMethodCallNode
{
	public function __construct(Expression\StaticMethodCallNode $from)
	{
		parent::__construct($from->class, $from->name, $from->args, $from->position);
	}


	public function print(PrintContext $context): string
	{
		return $this->isPartialFunction()
			? '$this->global->sandbox->closure(['
				. $context->memberAsString($this->class) . ', '
				. $context->memberAsString($this->name) . '])'
			: '$this->global->sandbox->call(['
				. $context->memberAsString($this->class) . ', '
				. $context->memberAsString($this->name) . '], '
				. $context->argumentsAsArray($this->args) . ')';
	}
}
