<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\PrintContext;


class UndefinedsafePropertyFetchNode extends Expression\UndefinedsafePropertyFetchNode
{
	public function __construct(Expression\UndefinedsafePropertyFetchNode $from)
	{
		parent::__construct($from->object, $from->name, $from->position);
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->prop('
			. $context->dereferenceExpr($this->object) . ' ?? null, '
			. $context->memberAsString($this->name) . ')'
			. '?->'
			. $context->objectProperty($this->name);
	}
}
