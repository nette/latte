<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Compiler\Nodes;

use Latte\Compiler\Node;


/**
 * Base for nodes representing template content that produces output.
 *
 * Includes text, HTML elements, and Latte tags. Extend StatementNode
 * for custom Latte tags, or AreaNode directly for passive content.
 */
abstract class AreaNode extends Node
{
}
