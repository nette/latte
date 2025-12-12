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


class StaticMethodCallNode extends Expression\StaticMethodCallNode
{
	public function __construct(Expression\StaticMethodCallNode $from)
	{
		parent::__construct($from->class, $from->name, $from->args, $from->position);
	}


	public function print(PrintContext $context): string
	{
		$pair = '[' . $context->memberAsString($this->class) . ', ' . $context->memberAsString($this->name) . ']';
		if (!$this->isPartialFunction()) {
			return '$this->global->sandbox->call(' . $pair . ', ' . $context->argumentsAsArray($this->args)[0] . ')';
		} elseif ($this->args[0] instanceof Php\VariadicPlaceholderNode) {
			return '$this->global->sandbox->closure(' . $pair . ')';
		} else {
			[$args, $params] = $context->argumentsAsArray($this->args);
			return '(fn(' . $params . ') => $this->global->sandbox->call(' . $pair . ', ' . $args . '))';
		}
	}
}
