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
	 * Returns template source code.
	 */
	function getContent($name);

	/**
	 * Checks whether template is expired.
	 */
	function isExpired($name, $time);

	/**
	 * Returns referred template name.
	 */
	function getReferredName($name, $referringName);

	/**
	 * Returns unique identifier for caching.
	 */
	function getUniqueId($name);
}


interface_exists(ILoader::class);
