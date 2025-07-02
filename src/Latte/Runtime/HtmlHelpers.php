<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Runtime;

use function strtolower;


/**
 * Runtime utilities for handling HTML.
 * @internal
 */
final class HtmlHelpers
{
	/**
	 * Checks if the given tag name represents a void (empty) HTML element.
	 */
	public static function isVoidElement(string $name): bool
	{
		static $names = [
			'img' => 1, 'hr' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'area' => 1, 'embed' => 1, 'keygen' => 1, 'source' => 1, 'base' => 1,
			'col' => 1, 'link' => 1, 'param' => 1, 'basefont' => 1, 'frame' => 1, 'isindex' => 1, 'wbr' => 1, 'command' => 1, 'track' => 1,
		];
		return isset($names[strtolower($name)]);
	}
}
