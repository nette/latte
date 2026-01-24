<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;


/**
 * Base for complex type declarations (nullable, union, intersection).
 */
abstract class ComplexTypeNode extends Node
{
}
