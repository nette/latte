<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes;


/**
 * Base for Latte tags like {if}, {foreach}, {block}.
 *
 * Extend this class when creating custom tags. Implement static create(Tag)
 * for parsing and print(PrintContext) for PHP code generation.
 */
abstract class StatementNode extends AreaNode
{
}
