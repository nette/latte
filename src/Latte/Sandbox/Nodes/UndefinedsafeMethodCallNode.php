<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\PrintContext;

class UndefinedsafeMethodCallNode extends Expression\UndefinedsafeMethodCallNode
{
    public function __construct(Expression\UndefinedsafeMethodCallNode $from)
    {
        parent::__construct($from->object, $from->name, $from->args, $from->position);
    }

    public function print(PrintContext $context): string
    {
        return '$this->global->sandbox->callMethod('
            . $this->object->print($context) . ' ?? null, '
            . $context->memberAsString($this->name) . ', '
            . Expression\ArrayNode::fromArguments($this->args)->print($context) . ', true)';
    }
}
