<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php\ArgumentNode;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\PrintContext;


/**
 * Method call routed through sandbox security policy.
 */
class MethodCallNode extends Expression\MethodCallNode
{
	public function __construct(Expression\MethodCallNode $from)
	{
		parent::__construct($from->object, $from->name, $from->args, $from->nullsafe, $from->position);
	}


	public function print(PrintContext $context): string
	{
		if ($this->isPartialFunction()) {
			return '$this->global->sandbox->closure(['
				. $this->object->print($context) . ', '
				. $context->memberAsString($this->name) . '])';
		}

		/** @var array<ArgumentNode> $args */
		$args = $this->args;
		return '$this->global->sandbox->callMethod('
			. $this->object->print($context) . ', '
			. $context->memberAsString($this->name) . ', '
			. $context->argumentsAsArray($args)
			. ', ' . var_export($this->nullsafe, return: true) . ')';
	}
}
