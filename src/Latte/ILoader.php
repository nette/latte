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
interface ILoader
{

	/**
	 * Returns template source code.
	 */
	function getContent($name): string;

	/**
	 * Checks whether template is expired.
	 */
	function isExpired($name, $time): bool;

	/**
	 * Returns referred template name.
	 */
	function getReferredName($name, $referringName): string;

	/**
	 * Returns unique identifier for caching.
	 */
	function getUniqueId($name): string;
}
