<?php declare(strict_types=1);

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte\Runtime;

use function is_string;


/**
 * Template runtime helpers.
 * @internal
 */
class Helpers
{
	/**
	 * Ensures the value is a string or returns null.
	 * @return ($value is string ? string : null)
	 */
	public static function stringOrNull(mixed $value): ?string
	{
		return is_string($value) ? $value : null;
	}
}
