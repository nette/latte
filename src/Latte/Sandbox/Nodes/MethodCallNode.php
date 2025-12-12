<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\PrintContext;


class MethodCallNode extends Expression\MethodCallNode
{
	public function __construct(Expression\MethodCallNode $from)
	{
		parent::__construct($from->object, $from->name, $from->args, $from->nullsafe, $from->position);
	}


	public function print(PrintContext $context): string
	{
		$pair = $this->object->print($context) . ', ' . $context->memberAsString($this->name);
		$nullsafe = var_export($this->nullsafe, return: true);
		if (!$this->isPartialFunction()) {
			return '$this->global->sandbox->callMethod(' . $pair . ', ' . $context->argumentsAsArray($this->args)[0] . ', ' . $nullsafe . ')';
		} elseif ($this->args[0] instanceof Php\VariadicPlaceholderNode) {
			return '$this->global->sandbox->closure([' . $pair . '])';
		} else {
			[$args, $params] = $context->argumentsAsArray($this->args);
			return '(fn(' . $params . ') => $this->global->sandbox->callMethod(' . $pair . ', ' . $args . ', ' . $nullsafe . '))';
		}
	}
}
