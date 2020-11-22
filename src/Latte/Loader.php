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
	 * @param  string  $name
	 * @return string
	 */
	function getContent($name);

	/**
	 * Checks whether template is expired.
	 * @param  string  $name
	 * @param  int  $time
	 * @return bool
	 */
	function isExpired($name, $time);

	/**
	 * Returns referred template name.
	 * @param  string  $name
	 * @param  string  $referringName
	 * @return string
	 */
	function getReferredName($name, $referringName);

	/**
	 * Returns unique identifier for caching.
	 * @param  string  $name
	 * @return string
	 */
	function getUniqueId($name);
}
