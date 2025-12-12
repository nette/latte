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


class FunctionCallNode extends Expression\FunctionCallNode
{
	public function __construct(Expression\FunctionCallNode $from)
	{
		parent::__construct($from->name, $from->args, $from->position);
	}


	public function print(PrintContext $context): string
	{
		$name = $context->memberAsString($this->name);
		if (!$this->isPartialFunction()) {
			return '$this->global->sandbox->call(' . $name . ', ' . $context->argumentsAsArray($this->args)[0] . ')';
		} elseif ($this->args[0] instanceof Php\VariadicPlaceholderNode) {
			return '$this->global->sandbox->closure(' . $name . ')';
		} else {
			[$args, $params] = $context->argumentsAsArray($this->args);
			return '(fn(' . $params . ') => $this->global->sandbox->call(' . $name . ', ' . $args . '))';
		}
	}
}
