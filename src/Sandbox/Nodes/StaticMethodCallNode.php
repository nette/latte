<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\PrintContext;


class StaticMethodCallNode extends Expression\StaticMethodCallNode
{
	public function __construct(Expression\StaticMethodCallNode $from)
	{
		parent::__construct($from->class, $from->name, $from->args, $from->position);
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->call(['
			. $context->memberAsString($this->class) . ', '
			. $context->memberAsString($this->name) . '], '
			. $context->argumentsAsArray($this->args) . ')';
	}
}
