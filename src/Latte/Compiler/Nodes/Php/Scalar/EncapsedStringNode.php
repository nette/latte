<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Scalar;

use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ScalarNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\Position;
use Latte\Compiler\PrintContext;

class EncapsedStringNode extends ScalarNode
{
    public function __construct(
        /** @var ExpressionNode[] */
        public array $parts,
        public ?Position $position = null,
    ) {
    }

    /** @param ExpressionNode[] $parts */
    public static function parse(array $parts, Position $position): static
    {
        foreach ($parts as $part) {
            if ($part instanceof EncapsedStringPartNode) {
                $part->value = PhpHelpers::decodeEscapeSequences($part->value, '"');
            }
        }

        return new static($parts, $position);
    }

    public function print(PrintContext $context): string
    {
        $s = '';
        $expr = false;
        foreach ($this->parts as $part) {
            if ($part instanceof EncapsedStringPartNode) {
                $s .= substr($context->encodeString($part->value, '"'), 1, -1);
            } elseif (
                $part instanceof Expression\VariableNode
                || $part instanceof Expression\PropertyFetchNode
                || $part instanceof Expression\NullsafePropertyFetchNode
                || $part instanceof Expression\MethodCallNode
                || $part instanceof Expression\NullsafeMethodCallNode
                || $part instanceof Expression\ArrayAccessNode
            ) {
                $s .= '{' . $part->print($context) . '}';
            } else {
                $s .= '" . (' . $part->print($context) . ') . "';
                $expr = true;
            }
        }

        return $expr
            ? '("' . $s . '")'
            : '"' . $s . '"';
    }

    public function &getIterator(): \Generator
    {
        foreach ($this->parts as &$item) {
            yield $item;
        }
    }
}
