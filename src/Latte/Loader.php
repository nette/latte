<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Template loader.
 */
interface Loader
{
	/**
	 * Loads template or static file content.
	 */
	//function load(string $name): LoadedContent;

	/**
	 * Returns referred template name.
	 */
	function getReferredName(string $name, string $referringName): string;

	/**
	 * Returns unique identifier for caching.
	 */
	function getUniqueId(string $name): string;
}
