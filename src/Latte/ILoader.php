<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

namespace Latte;


/**
 * Template loader.
 */
interface ILoader
{

	/**
	 * Returns template source code.
	 * @return string
	 */
	function getContent($name);

	/**
	 * Checks whether template is expired.
	 * @return bool
	 */
	function isExpired($name, $time);

	/**
	 * Returns referred template name.
	 * @return string
	 */
	function getReferredName($name, $referringName);

	/**
	 * Returns unique identifier for caching.
	 * @return string
	 */
	function getUniqueId($name);
}
