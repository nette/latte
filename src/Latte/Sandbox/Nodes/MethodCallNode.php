<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\PrintContext;


/**
 * Method call routed through sandbox security policy.
 */
class MethodCallNode extends Expression\MethodCallNode
{
	use MemberAccess;

	public function __construct(Expression\MethodCallNode $from)
	{
		parent::__construct($from->object, $from->name, $from->args, $from->nullsafe, $from->position);
	}


	public function print(PrintContext $context): string
	{
		if ($this->isPartialApplication()) {
			return '$this->global->sandbox->closure(['
				. $this->object->print($context) . ', '
				. $context->memberAsString($this->name) . '])';
		}

		[$name, $member] = $this->printName($context, $this->name);
		return '$this->global->sandbox->method('
			. $this->object->print($context) . ', '
			. $name . ')'
			. $this->printOperator()
			. $member
			. '(' . ($this->args ? '...$this->global->sandbox->args(' . $context->implode($this->args) . ')' : '') . ')';
	}
}
