<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;


/**
 * Marks a value as pre-escaped HTML that should not be escaped again when rendered.
 */
interface HtmlStringable
{
	/** Returns content in HTML format (no further escaping needed). */
	function __toString(): string;
}
