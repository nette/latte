<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Attributes;

use Attribute;


/**
 * Marks method as a template function.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class TemplateFunction
{
}


/**
 * Marks method as a template filter.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class TemplateFilter
{
}
